<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Form;

use Symfony\Component\Form\FormInterface;

interface RapidFormBuilderInterface
{
    public function create(object $data, array $options = [], ?string $name = null): FormInterface;
}
