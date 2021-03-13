<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Tests\Form;

use Ansien\RapidFormBundle\Form\RapidFormBuilder;
use Ansien\RapidFormBundle\Tests\TestClasses\CollectionForm;
use Ansien\RapidFormBundle\Tests\TestClasses\FormAttributesForm;
use Ansien\RapidFormBundle\Tests\TestClasses\InvalidForm;
use Ansien\RapidFormBundle\Tests\TestClasses\NestedForm;
use Ansien\RapidFormBundle\Tests\TestClasses\RepeatedForm;
use Ansien\RapidFormBundle\Tests\TestClasses\TestForm;
use Closure;
use InvalidArgumentException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Test\TypeTestCase;

class RapidFormBuilderTest extends TypeTestCase
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

        $formBuilder = new RapidFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertInstanceOf(Form::class, $form);
    }

    public function testInvalidFails(): void
    {
        $data = new InvalidForm();

        $formBuilder = new RapidFormBuilder($this->factory);

        try {
            $formBuilder->create($data);
        } catch (InvalidArgumentException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testFormAttributes(): void
    {
        $data = new FormAttributesForm();

        $formBuilder = new RapidFormBuilder($this->factory);
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

        $formBuilder = new RapidFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertEquals(['EUR', 'USD'], $form->get('currency')->getConfig()->getOption('choices'));
        $this->assertInstanceOf(Closure::class, $form->get('currency')->getConfig()->getOption('choice_label'));
    }

    public function testNestedForm(): void
    {
        $data = new NestedForm();

        $formBuilder = new RapidFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        self::assertInstanceOf(Form::class, $form->get('childForm')->get('name'));
        self::assertEquals('12345', $form->get('childForm')->get('name')->getData());
    }

    public function testCollectionForm(): void
    {
        $data = new CollectionForm();

        $formBuilder = new RapidFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        self::assertInstanceOf(Form::class, $form->get('items')->all()[0]->get('name'));
        self::assertEquals('0', $form->get('items')->all()[0]->get('name')->getData());
    }

    public function testRepeatedForm(): void
    {
        $data = new RepeatedForm();

        $formBuilder = new RapidFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        self::assertInstanceOf(Form::class, $form->get('repeated')->get('second'));
        self::assertEquals('Label 2', $form->get('repeated')->get('second')->getConfig()->getOption('label'));
    }
}
