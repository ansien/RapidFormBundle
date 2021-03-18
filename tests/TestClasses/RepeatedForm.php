<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

#[Form]
class RepeatedForm
{
    #[FormField(RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => ['label' => 'Label 1'],
        'second_options' => ['label' => 'Label 2'],
    ])]
    public ?string $password = null;
}
