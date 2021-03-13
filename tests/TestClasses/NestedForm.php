<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;

#[Form]
class NestedForm
{
    #[FormField(ChildForm::class)]
    public ?ChildForm $childForm = null;

    public function __construct()
    {
        $this->childForm = new ChildForm('12345');
    }
}
