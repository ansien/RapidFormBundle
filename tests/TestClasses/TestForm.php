<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\TestClasses;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Ansien\RapidFormBundle\Form\CallbackType;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
    public ?string $currency = null;

    #[FormField(NumberType::class, [
        'scale' => 4,
        'input' => 'string',
    ])]
    public ?string $rate = null;

    #[FormField(DateType::class, [
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
    ])]
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
