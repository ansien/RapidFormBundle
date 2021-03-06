<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests;

use Ansien\AttributeFormBundle\Form\AttributeFormBuilder;
use Ansien\AttributeFormBundle\Tests\TestClasses\TestForm;
use Closure;
use Symfony\Component\Form\Form;
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

    public function testCallbacks(): void
    {
        $data = new TestForm(
            'exampleValue',
            ['EUR', 'USD'],
        );

        $formBuilder = new AttributeFormBuilder($this->factory);
        $form = $formBuilder->create($data);

        $this->assertEquals($form->get('currency')->getConfig()->getOption('choices'), ['EUR', 'USD']);
        $this->assertInstanceOf(Closure::class, $form->get('currency')->getConfig()->getOption('choice_label'));
    }
}
