<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class FormField
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {
    }
}
