<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Form;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class AbstractRapidFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributes = $this->resolveAttributes($options);

        if ($attributes === null) {
            return;
        }

        $this->applyPropertyAttributes($attributes['properties'], $builder);
        $this->applyClassAttributes($attributes['class'], $builder);
    }

    /**
     * @param <string, ReflectionClass>[] $properties
     */
    protected function applyPropertyAttributes(?array $properties, FormBuilderInterface $builder): void
    {
        foreach ($properties ?? [] as $fieldName => $propertyData) {
            foreach ($propertyData['attributes'] as $attribute) {
                switch ($attribute::class) {
                    case FormField::class:
                        $this->addField($fieldName, $attribute, $builder);
                        break;
                }
            }
        }
    }

    protected function applyClassAttributes(Form $formAttribute, FormBuilderInterface $builder): void
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

    protected function addField(string $fieldName, FormField $formField, FormBuilderInterface $builder): void
    {
        $options = $this->transformOptions($builder->getData(), $formField->options);

        $type = $formField->type;
        $typeReflectionClass = new ReflectionClass($type);

        // Handle embeddable types
        if (!$typeReflectionClass->implementsInterface(FormTypeInterface::class) && !empty($typeReflectionClass->getAttributes(Form::class))) {
            $builder->add($fieldName, AbstractRapidFormType::class, [
                'data_class' => $type,
            ]);

            return;
        }

        // Handle CollectionType
        if ($type === CollectionType::class) {
            $entryOptions['data_class'] = $formField->options['entry_type'];

            $collectionOptions = array_merge($options, [
                'entry_type' => AbstractRapidFormType::class,
                'entry_options' => $entryOptions,
            ]);

            $builder->add($fieldName, CollectionType::class, $collectionOptions);

            return;
        }

        $builder->add(
            $fieldName,
            $formField->type,
            $options,
        );
    }

    protected function transformOptions(mixed $dataClass, array $options): array
    {
        $transformedOptions = $options;
        foreach ($options as $optionKey => $option) {
            if (!is_array($option) || count($option) !== 2 || !isset($option[0])) {
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

    private function resolveAttributes(array $options): ?array
    {
        if (isset($options['data_class'])) {
            $entryType = $options['data_class'];
        } elseif (isset($options['entry_options']['entry_type'])) {
            $entryType = $options['entry_options']['entry_type'];
        } elseif (isset($options['entry_type'])) {
            $entryType = $options['entry_type'];
        } else {
            return null;
        }

        $reflectionClass = new ReflectionClass($entryType);

        $propertyAttributes = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes();

            foreach ($attributes as $attribute) {
                $propertyAttributes[$propertyName]['attributes'][] = $attribute->newInstance();
            }
        }

        return [
            'class' => $reflectionClass->getAttributes(Form::class)[0]->newInstance(),
            'properties' => $propertyAttributes,
        ];
    }
}
