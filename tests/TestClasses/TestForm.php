<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests\TestClasses;

use Ansien\AttributeFormBundle\Attribute\Form;
use Ansien\AttributeFormBundle\Attribute\FormField;
use Ansien\AttributeFormBundle\Form\CallbackType;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

#[Form]
class TestForm
{
    private string $exampleValue;

    public array $enabledCurrencyChoices;

    // region Form fields

    #[FormField(ChoiceType::class, [
        'choices' => [CallbackType::VALUE, 'enabledCurrencyChoices'],
        'choice_label' => [CallbackType::FUNCTION, 'getCurrencyLabelCb'],
    ])]
    #[Assert\NotBlank]
    public ?string $currency = null;

    #[FormField(NumberType::class, [
        'scale' => 4,
        'input' => 'string',
    ])]
    #[Assert\NotBlank]
    #[Assert\Range(min: -1000000, max: 1000000)]
    public ?string $rate = null;

    #[FormField(DateType::class, [
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
    ])]
    #[Assert\NotBlank]
    public ?DateTimeImmutable $date = null;

    // endregion

    public function __construct(string $exampleValue, array $enabledCurrencyChoices)
    {
        $this->exampleValue = $exampleValue;
        $this->enabledCurrencyChoices = $enabledCurrencyChoices;
    }

    public function getCurrencyLabelCb(): callable
    {
        return fn (string $value) => $value . ' + TEST';
    }

    public function getExampleValue(): string
    {
        return $this->exampleValue;
    }
}
