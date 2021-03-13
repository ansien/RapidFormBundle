<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

#[Form]
class CollectionTypeExample
{
    #[FormField(CollectionType::class, [
        'entry_type' => CollectionTypeChildExample::class,
        'allow_add' => true,
    ])]
    public ?array $items = [];

    public function __construct()
    {
        $this->items = [new CollectionTypeChildExample('Test')];
    }
}
