<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Form;

use Ansien\AttributeFormBundle\Attribute\Form;
use Ansien\AttributeFormBundle\Util\NamingUtils;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class AttributeFormBuilder implements AttributeFormBuilderInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
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
            AbstractAttributeType::class,
            $data,
            $options
        );
    }
}
