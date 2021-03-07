<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Form
{
    public function __construct(
        public ?string $action = null,
        public ?string $method = null,
        public ?string $submit = null,
        public ?bool $disabled = null,
        public ?array $attributes = null,
    ) {
    }
}
