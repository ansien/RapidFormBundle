<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Form;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use BadMethodCallException;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
        foreach ($properties ?? [] as $propertyName => $propertyData) {
            foreach ($propertyData['attributes'] ?? [] as $attribute) {
                switch ($attribute::class) {
                    case FormField::class:
                        $this->addField($propertyName, $attribute, $propertyData['data'], $builder);
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

    protected function addField(string $fieldName, FormField $formField, mixed $formData, FormBuilderInterface $builder): void
    {
        $options = $this->transformOptions($formData, $formField->options);

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
            if (!isset($options['entry_type'])) {
                throw new BadMethodCallException('"entry_type" must be configured for CollectionType to function properly.');
            }

            $entryOptions['data_class'] = $formField->options['entry_type'];

            $nestedOptions = array_merge($options, [
                'entry_type' => AbstractRapidFormType::class,
                'entry_options' => $entryOptions,
            ]);

            $builder->add($fieldName, CollectionType::class, $nestedOptions);

            return;
        }

        // Handle RepeatedType
        if ($type === RepeatedType::class) {
            $entryOptions['data_class'] = $formField->options['type'];

            $nestedOptions = array_merge($options, [
                'type' => AbstractRapidFormType::class,
            ]);

            $builder->add($fieldName, RepeatedType::class, $nestedOptions);

            return;
        }

        $builder->add(
            $fieldName,
            $formField->type,
            $options,
        );
    }

    protected function transformOptions(mixed $formData, array $options): array
    {
        $transformedOptions = $options;

        foreach ($options as $optionKey => $option) {
            if (!is_array($option) || count($option) !== 2 || !isset($option[0])) {
                continue;
            }

            if ($option[0] === CallbackType::VALUE) {
                $valueKey = $option[1];
                $transformedOptions[$optionKey] = $formData->$valueKey;
            }

            if ($option[0] === CallbackType::FUNCTION) {
                $transformedOptions[$optionKey] = call_user_func([$formData, $option[1]]);
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

            if (isset($options['data'])) {
                $data = $options['data'];
            } else {
                $data = new $entryType();
            }

            $propertyAttributes[$propertyName]['data'] = $data;
        }

        return [
            'class' => $reflectionClass->getAttributes(Form::class)[0]->newInstance(),
            'properties' => $propertyAttributes,
        ];
    }
}
