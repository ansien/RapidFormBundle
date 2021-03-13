<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;

#[Form]
class NestedFormExample
{
    #[FormField(SimpleExample::class)]
    public ?SimpleExample $nestedForm = null;

    public function __construct()
    {
        $this->nestedForm = new SimpleExample();
    }
}
