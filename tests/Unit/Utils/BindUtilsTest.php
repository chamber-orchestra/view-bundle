<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use ChamberOrchestra\ViewBundle\Attribute\Type;
use ChamberOrchestra\ViewBundle\PropertyAccessor\ReflectionService;
use ChamberOrchestra\ViewBundle\Utils\BindUtils;
use ChamberOrchestra\ViewBundle\View\BindView;
use ChamberOrchestra\ViewBundle\View\IterableView;

final class BindUtilsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetStaticState();
    }

    protected function tearDown(): void
    {
        $this->resetStaticState();
    }

    public function testConfigureRunsOnlyOnce(): void
    {
        BindUtils::configure('first', 123, 'ns_first');
        BindUtils::configure('second', 999, 'ns_second');

        self::assertSame('first', $this->getBindUtilsProperty('version'));
        self::assertSame(123, $this->getBindUtilsProperty('cacheLifetime'));
        self::assertSame('ns_first', $this->getBindUtilsProperty('cacheNamespace'));
        self::assertTrue($this->getBindUtilsProperty('configured'));
    }

    public function testSyncCopiesMissingValuesAndLeavesExistingOnes(): void
    {
        $source = new class {
            public string $name = 'Alice';
            public int $age = 30;
        };

        $target = new class {
            public ?string $name = null;
            public int $age = 5;
        };

        BindUtils::instance()->sync($target, $source);

        self::assertSame('Alice', $target->name);
        self::assertSame(5, $target->age, 'Existing non-null value must not be overridden');

        $storage = $this->getBindUtilsProperty('storage');
        self::assertCount(1, $storage, 'Intersection cache should contain computed mapping');
    }

    public function testSyncMapsViewAndIterableViewProperties(): void
    {
        $child = new class {
            public string $id = 'child-id';
        };

        $source = new class($child) {
            public object $child;
            public array $children;

            public function __construct(object $child)
            {
                $this->child = $child;
                $this->children = [$child];
            }
        };

        $target = new class {
            public ?ChildView $child = null;
            #[Type(ChildView::class)]
            public IterableView $children;
        };

        BindUtils::instance()->sync($target, $source);

        self::assertInstanceOf(ChildView::class, $target->child);
        self::assertSame($child, $target->child->source);

        self::assertInstanceOf(IterableView::class, $target->children);
        self::assertCount(1, $target->children->entries);
        self::assertInstanceOf(ChildView::class, $target->children->entries[0]);
        self::assertSame($child, $target->children->entries[0]->source);
    }

    private function getBindUtilsProperty(string $property): mixed
    {
        return new \ReflectionProperty(BindUtils::class, $property)->getValue();
    }

    private function resetStaticState(): void
    {
        foreach ([
                     'configured' => false,
                     'cacheNamespace' => 'bind_view',
                     'cacheLifetime' => 0,
                     'version' => '',
                     'storage' => [],
                 ] as $prop => $value) {
            new \ReflectionProperty(BindUtils::class, $prop)->setValue(null, $value);
        }

        new \ReflectionProperty(ReflectionService::class, 'storage')->setValue(null, []);
    }
}

final class ChildView extends BindView
{
    public function __construct(public object $source)
    {
        parent::__construct($source);
    }
}
