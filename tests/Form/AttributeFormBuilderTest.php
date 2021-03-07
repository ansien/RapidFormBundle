<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests\Form;

use Ansien\AttributeFormBundle\Form\AttributeFormBuilder;
use Ansien\AttributeFormBundle\Tests\TestClasses\FormAttributesForm;
use Ansien\AttributeFormBundle\Tests\TestClasses\InvalidForm;
use Ansien\AttributeFormBundle\Tests\TestClasses\TestForm;
use Closure;
use InvalidArgumentException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Test\TypeTestCase;

class AttributeFormBuilderTest extends TypeTestCase
{
    public function testCanInitialize(): void
    {
        $data = new TestForm(
            'exampleValue',
            ['EUR', 'USD'],
        );

        $this->assertIsObject($data);
        $this->assertEquals('exampleValue', $data->getExampleValue());
    }

    public function testCanBuild(): void
    {
        $data = new TestForm(
            'exampleValue',
            ['EUR', 'USD'],
        );

        $formBuilder = new AttributeFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertInstanceOf(Form::class, $form);
    }

    public function testInvalidFails(): void
    {
        $data = new InvalidForm();

        $formBuilder = new AttributeFormBuilder($this->factory);

        try {
            $formBuilder->create($data);
        } catch (InvalidArgumentException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testFormAttributes(): void
    {
        $data = new FormAttributesForm();

        $formBuilder = new AttributeFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertEquals('test', $form->getConfig()->getAction());
        $this->assertEquals('POST', $form->getConfig()->getMethod());
        $this->assertInstanceOf(SubmitButton::class, $form->get('_submit'));
        $this->assertEquals(true, $form->isDisabled());
        $this->assertEquals(['hello' => 'world'], $form->getConfig()->getAttributes());
    }

    public function testCallbacks(): void
    {
        $data = new TestForm(
            'exampleValue',
            ['EUR', 'USD'],
        );

        $formBuilder = new AttributeFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertEquals(['EUR', 'USD'], $form->get('currency')->getConfig()->getOption('choices'));
        $this->assertInstanceOf(Closure::class, $form->get('currency')->getConfig()->getOption('choice_label'));
    }
}
