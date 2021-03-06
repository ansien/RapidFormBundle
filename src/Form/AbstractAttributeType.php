<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Form;

use Ansien\AttributeFormBundle\Attribute\FormField;
use Cocur\Slugify\Slugify;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AbstractAttributeType extends AbstractType
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

    protected function applyClassAnnotations(array $classAnnotations, FormBuilderInterface $builder): void
    {
        // @TODO
    }

    protected function addField(string $fieldName, FormField $propertyAnnotation, FormBuilderInterface $builder): void
    {
        $options = $this->transformOptions($builder->getData(), $propertyAnnotation->options);

        if (!isset($options['label'])) {
            $options['label'] = sprintf(
                '%s.%s',
                self::getSlugify()->slugify($builder->getName()),
                self::getSlugify()->slugify($fieldName)
            );
        }

        $builder->add(
            $fieldName,
            $propertyAnnotation->type,
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
            'class' => $reflectionClass->getAttributes(Form::class),
            'properties' => $propertyAnnotations,
        ];
    }

    protected static function getSlugify(): Slugify
    {
        return new Slugify(
            [
                'regexp' => '/(?<=[[:^upper:]])(?=[[:upper:]])/',
                'separator' => '_',
                'lowercase_after_regexp' => true,
            ]
        );
    }
}
