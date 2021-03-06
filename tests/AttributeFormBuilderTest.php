<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests;

use Ansien\AttributeFormBundle\Tests\TestClasses\TestForm;
use PHPUnit\Framework\TestCase;

class AttributeFormBuilderTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $form = new TestForm(
            'exampleValue',
            ['EUR', 'USD'],
        );

        $this->assertIsObject($form);
    }
}
