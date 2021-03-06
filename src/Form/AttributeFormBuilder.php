<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Form;

use InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class AttributeFormBuilder implements AttributeFormBuilderInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(mixed $data, array $options = [], ?string $name = null): FormInterface
    {
        if (!is_object($data)) {
            throw new InvalidArgumentException('Data should be an object');
        }

        if ($name === null) {
            $parts = explode('\\', get_class($data));
            $class = end($parts);
            $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class));
        }

        return $this->formFactory->createNamed(
            $name,
            AbstractAttributeType::class,
            $data,
            $options
        );
    }
}
