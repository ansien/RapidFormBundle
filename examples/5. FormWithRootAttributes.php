<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[Form(action: 'test', method: 'POST', submit: 'submit', disabled: true, attributes: ['hello' => 'world'])]
class FormWithRootAttributes
{
    #[FormField(TextType::class, [
        'required' => true,
    ])]
    #[Assert\NotBlank]
    public ?string $name = null;

    #[FormField(TextType::class)]
    public ?string $description = null;
}
