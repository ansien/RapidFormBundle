<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

#[Form]
class CollectionForm
{
    #[FormField(CollectionType::class, [
        'entry_type' => ChildForm::class,
    ])]
    public ?array $items = [];

    public function __construct()
    {
        $this->items = [new ChildForm('0'), new ChildForm('1')];
    }
}
