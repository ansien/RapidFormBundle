<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Examples;

use Ansien\AttributeFormBundle\Attribute\AttributeForm;
use Ansien\AttributeFormBundle\Attribute\AttributeFormField;
use App\Internal\AnnotatedForm\Annotation\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[AttributeForm]
class SimpleExample
{
    #[AttributeFormField(TextType::class, [
        'required' => true,
    ])]
    #[Assert\NotBlank]
    public ?string $name = null;

    #[AttributeFormField(TextType::class)]
    public ?string $description = null;
}
