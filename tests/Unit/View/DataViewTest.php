<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ChamberOrchestra\ViewBundle\View\DataView;

final class DataViewTest extends TestCase
{
    public function testItWrapsDataUnderDataKey(): void
    {
        $payload = ['foo' => 'bar'];
        $view = new DataView($payload);

        $normalizer = $this->createMock(NormalizerInterface::class);
        $normalizer
            ->expects(self::once())
            ->method('normalize')
            ->with(['data' => $payload], 'json', ['ctx' => true])
            ->willReturn(['data' => $payload]);

        $result = $view->normalize($normalizer, 'json', ['ctx' => true]);

        self::assertSame(['data' => $payload], $result);
    }
}
