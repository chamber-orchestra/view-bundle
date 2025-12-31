<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\ViewBundle\View;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DataView extends ResponseView
{
    public function __construct(
        public readonly ViewInterface|array $data,
    ) {
        parent::__construct();
    }

    public function normalize(NormalizerInterface $normalizer, ?string $format = null, array $context = []): array|string|int|float|bool
    {
        return $normalizer->normalize(['data' => $this->data], $format, $context);
    }
}