<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[Form(eventSubscribers: [ExampleEventSubscriber::class])]
class EventSubscriberFormExample
{
    #[FormField(TextType::class)]
    public ?string $description = null;
}
