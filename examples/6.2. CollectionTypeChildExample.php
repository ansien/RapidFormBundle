<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[Form]
class CollectionTypeChildExample
{
    #[FormField(TextType::class)]
    public ?string $name = null;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }
}
