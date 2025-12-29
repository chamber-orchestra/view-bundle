<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ChamberOrchestra\ViewBundle\View\KeyValueView;

final class KeyValueViewTest extends TestCase
{
    public function testItReturnsKeyWithViewArray(): void
    {
        $view = new KeyValueView('meta', ['page' => 1]);

        $normalizer = $this->createMock(NormalizerInterface::class);
        $normalizer->expects(self::never())->method('normalize');

        $result = $view->normalize($normalizer);

        self::assertSame(['meta' => ['page' => 1]], $result);
    }
}
