<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Examples;

use Ansien\AttributeFormBundle\Attribute\Form;
use Ansien\AttributeFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[Form]
class SimpleExample
{
    #[FormField(TextType::class, [
        'required' => true,
    ])]
    #[Assert\NotBlank]
    public ?string $name = null;

    #[FormField(TextType::class)]
    public ?string $description = null;
}
