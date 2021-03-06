<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Attribute;

use Attribute;

#[Attribute]
class AttributeFormField
{
    public function __construct(
        public string $type,
        public array $options,
    ) {
    }
}
