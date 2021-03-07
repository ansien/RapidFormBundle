<?php

declare(strict_types=1);

namespace Ansien\AttributeFormBundle\Tests\Util;

use Ansien\AttributeFormBundle\Tests\TestClasses\EmptyForm;
use Ansien\AttributeFormBundle\Util\NamingUtils;
use PHPUnit\Framework\TestCase;

class NamingUtilsTest extends TestCase
{
    public function testClassToSnake(): void
    {
        $output = NamingUtils::classToSnake(new EmptyForm());

        $this->assertEquals('empty_form', $output);
    }

    public function testStringToSnake(): void
    {
        $output = NamingUtils::stringToSnake('TestVariable');

        $this->assertEquals('test_variable', $output);
    }
}
