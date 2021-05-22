<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;

class ExampleEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event): void
    {
        $product = $event->getData();
        $form = $event->getForm();

        if (!$product || null === $product->getId()) {
            $form->add('name', TextType::class);
        }
    }
}
