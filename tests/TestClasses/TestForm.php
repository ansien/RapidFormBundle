<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests\TestClasses;

use Ansien\AttributeFormBundle\Attribute\AttributeForm;
use Ansien\AttributeFormBundle\Attribute\AttributeFormField;
use Ansien\AttributeFormBundle\Form\CallbackType;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

#[AttributeForm]
class TestForm
{
    private string $exampleValue;

    public array $enabledCurrencies;

    // region Form fields

    #[AttributeFormField(ChoiceType::class, [
        'choices' => [CallbackType::VALUE, 'enabledCurrencies'],
        'choice_label' => [CallbackType::FUNCTION, 'getFromCurrencyLabel'],
    ])]
    #[Assert\NotBlank]
    public ?string $fromCurrency = null;

    #[AttributeFormField(ChoiceType::class, [
        'choices' => [CallbackType::VALUE, 'enabledCurrencies'],
    ])]
    #[Assert\NotBlank]
    public ?string $toCurrency = null;

    #[AttributeFormField(NumberType::class, [
        'scale' => 4,
        'input' => 'string',
    ])]
    #[Assert\NotBlank]
    #[Assert\Range(min: -1000000, max: 1000000)]
    public ?string $rate = null;

    #[AttributeFormField(DateType::class, [
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
    ])]
    #[Assert\NotBlank]
    public ?DateTimeImmutable $validFrom = null;

    #[AttributeFormField(DateType::class, [
        'widget' => 'single_text',
        'input' => 'datetime_immutable',
    ])]
    #[Assert\NotBlank]
    public ?DateTimeImmutable $validTo = null;

    // endregion

    public function __construct(string $exampleValue, array $enabledCurrencies)
    {
        $this->exampleValue = $exampleValue;
        $this->enabledCurrencies = $enabledCurrencies;
    }

    public function getFromCurrencyLabel(): callable
    {
        return fn ($value) => $value . ' + TEST';
    }

    #[Assert\Callback]
    public function validate(ExecutionContext $context): void
    {
        $data = $context->getObject();
        if (!($data instanceof self)) {
            return;
        }

        if ($data->fromCurrency === $data->toCurrency) {
            $context
                ->buildViolation('currency_rate.error.duplicate')
                ->atPath('fromCurrency')
                ->addViolation();
        }
    }

    public function getExampleValue(): string
    {
        return $this->exampleValue;
    }
}
