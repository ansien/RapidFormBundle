<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[Form]
class ChildForm
{
    #[FormField(TextType::class)]
    public ?string $name;

    #[FormField(TextareaType::class)]
    public ?string $description = null;

    #[FormField(CollectionType::class, [
        'entry_type' => NestedChildForm::class,
        'allow_add' => true,
    ])]
    public ?array $nestedItems = null;

    public function __construct(?string $name = null)
    {
        $this->name = $name;

        $this->nestedItems = [new NestedChildForm(), new NestedChildForm()];
    }
}
