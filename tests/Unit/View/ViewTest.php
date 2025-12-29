<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use PHPUnit\Framework\TestCase;
use ChamberOrchestra\ViewBundle\View\View;
use ChamberOrchestra\ViewBundle\View\ViewInterface;

final class ViewTest extends TestCase
{
    public function testAbstractViewImplementsInterface(): void
    {
        $view = new class extends View {
        };

        self::assertInstanceOf(ViewInterface::class, $view);
    }
}
