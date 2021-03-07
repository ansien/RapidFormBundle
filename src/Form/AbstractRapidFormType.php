<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Form;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Ansien\RapidFormBundle\Util\NamingUtils;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AbstractRapidFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annotations = $this->resolveAnnotations($options['data_class']);

        $this->applyPropertyAnnotations($annotations['properties'], $builder);
        $this->applyClassAnnotations($annotations['class'], $builder);
    }

    /**
     * @param <string, ReflectionClass>[] $properties
     */
    protected function applyPropertyAnnotations(?array $properties, FormBuilderInterface $builder): void
    {
        foreach ($properties ?? [] as $fieldName => $propertyAnnotations) {
            foreach ($propertyAnnotations as $propertyAnnotation) {
                switch (get_class($propertyAnnotation)) {
                    case FormField::class:
                        $this->addField($fieldName, $propertyAnnotation, $builder);
                        break;
                }
            }
        }
    }

    protected function applyClassAnnotations(Form $formAttribute, FormBuilderInterface $builder): void
    {
        if ($action = $formAttribute->action) {
            $builder->setAction($action);
        }

        if ($method = $formAttribute->method) {
            $builder->setMethod($method);
        }

        if ($disabled = $formAttribute->disabled) {
            $builder->setDisabled($disabled);
        }

        if ($attributes = $formAttribute->attributes) {
            $builder->setAttributes($attributes);
        }

        if ($submit = $formAttribute->submit) {
            $builder->add('_submit', SubmitType::class, ['label' => $submit]);
        }
    }

    protected function addField(string $fieldName, FormField $fieldAttribute, FormBuilderInterface $builder): void
    {
        $options = $this->transformOptions($builder->getData(), $fieldAttribute->options);

        if (!isset($options['label'])) {
            $options['label'] = sprintf(
                '%s.%s',
                NamingUtils::stringToSnake($builder->getName()),
                NamingUtils::stringToSnake($fieldName),
            );
        }

        $builder->add(
            $fieldName,
            $fieldAttribute->type,
            $options
        );
    }

    protected function transformOptions(object $dataClass, array $options): array
    {
        $transformedOptions = $options;
        foreach ($options as $optionKey => $option) {
            if (!is_array($option)) {
                continue;
            }

            if ($option[0] === CallbackType::VALUE) {
                $valueKey = $option[1];
                $transformedOptions[$optionKey] = $dataClass->$valueKey;
            }

            if ($option[0] === CallbackType::FUNCTION) {
                $transformedOptions[$optionKey] = call_user_func([$dataClass, $option[1]]);
            }
        }

        return $transformedOptions;
    }

    private function resolveAnnotations(string $formClass): array
    {
        $reflectionClass = new ReflectionClass($formClass);

        $propertyAnnotations = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes(FormField::class);

            foreach ($attributes as $attribute) {
                $propertyAnnotations[$propertyName][] = $attribute->newInstance();
            }
        }

        return [
            'class' => $reflectionClass->getAttributes(Form::class)[0]->newInstance(),
            'properties' => $propertyAnnotations,
        ];
    }
}