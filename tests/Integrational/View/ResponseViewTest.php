<?php

declare(strict_types=1);

namespace Tests\Integrational\View;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use ChamberOrchestra\ViewBundle\View\ResponseView;

final class ResponseViewTest extends KernelTestCase
{
    public function testSerializeReturnsEmptyPayload(): void
    {
        static::bootKernel();
        $serializer = static::getContainer()->get(SerializerInterface::class);

        $result = $serializer->normalize(new ResponseView(), 'json');

        self::assertSame([], $result);
    }
}
