<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;

#[Form(eventSubscribers: [EventSubscriber::class])]
class EventSubscriberForm
{
    public string $test;
}
