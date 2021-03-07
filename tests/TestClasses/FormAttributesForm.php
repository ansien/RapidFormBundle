<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests\TestClasses;

use Ansien\AttributeFormBundle\Attribute\Form;

#[Form(action: 'test', method: 'POST', submit: 'submit', disabled: true, attributes: ['hello' => 'world'])]
class FormAttributesForm
{
}
