<?php

declare(strict_types=1);

namespace Tests\Integrational\View;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use ChamberOrchestra\ViewBundle\View\IterableView;
use ChamberOrchestra\ViewBundle\View\View;

final class IterableViewTest extends KernelTestCase
{
    public function testSerializesMappedEntries(): void
    {
        $items = [(object)['id' => 1], (object)['id' => 2]];
        $view = new IterableView($items, DummyChildView::class);

        static::bootKernel();
        $serializer = static::getContainer()->get(SerializerInterface::class);

        $result = $serializer->normalize($view, 'json');

        self::assertSame([
            ['id' => 1],
            ['id' => 2],
        ], $result);
    }

}

final class DummyChildView extends View
{
    public int $id;

    public function __construct(object $source)
    {
        $this->id = $source->id;
    }
}
