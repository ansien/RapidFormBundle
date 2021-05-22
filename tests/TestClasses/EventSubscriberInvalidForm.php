<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;

#[Form(eventSubscribers: ['InvalidSubscriberClass'])]
class EventSubscriberInvalidForm
{
    public string $test;
}
