<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;

#[Form(action: 'test', method: 'POST', submit: 'submit', disabled: true, attributes: ['hello' => 'world'])]
class FormAttributesForm
{
}
