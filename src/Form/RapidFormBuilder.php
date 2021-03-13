<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Form;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Util\NamingUtils;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RapidFormBuilder implements RapidFormBuilderInterface
{
    public function __construct(private FormFactoryInterface $formFactory)
    {
    }

    public function create(object $data, array $options = [], ?string $name = null): FormInterface
    {
        $reflectedForm = new ReflectionClass($data);

        if (empty($reflectedForm->getAttributes(Form::class))) {
            throw new InvalidArgumentException(sprintf('Supplied data object does not use the required %s attribute.', Form::class));
        }

        if ($name === null) {
            $name = NamingUtils::classToSnake($data);
        }

        return $this->formFactory->createNamed(
            $name,
            AbstractRapidFormType::class,
            $data,
            $options
        );
    }
}
