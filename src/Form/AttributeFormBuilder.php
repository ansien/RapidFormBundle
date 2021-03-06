<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Form;

use InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class AttributeFormBuilder
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
            $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class));
            $name = str_replace('_data', '', $snake);
        }

        return $this->formFactory->createNamed(
            $name,
            AbstractAttributeType::class,
            $data,
            $options
        );
    }
}
