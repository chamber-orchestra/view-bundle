<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use ChamberOrchestra\ViewBundle\Attribute\Type;

final class TypeTest extends TestCase
{
    public function testAttributeIsRegisteredForProperties(): void
    {
        $reflection = new \ReflectionClass(Type::class);
        $attributes = $reflection->getAttributes(\Attribute::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame([\Attribute::TARGET_PROPERTY], $attributes[0]->getArguments());
    }

    public function testAttributeStoresTargetClass(): void
    {
        $property = new \ReflectionProperty(DummyView::class, 'images');
        $attributes = $property->getAttributes(Type::class);

        $this->assertCount(1, $attributes);
        $instance = $attributes[0]->newInstance();

        $this->assertSame(DummyImageView::class, $instance->class);
    }
}

final class DummyView
{
    #[Type(DummyImageView::class)]
    public array $images = [];
}

final class DummyImageView
{
}
