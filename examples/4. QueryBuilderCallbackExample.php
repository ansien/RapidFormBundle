<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Examples;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Ansien\RapidFormBundle\Form\CallbackType;
use Closure;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use TestEntity;


#[Form]
class QueryBuilderCallbackExample
{
    #[FormField(EntityType::class, [
        'class' => TestEntity::class,
        'choice_label' => 'name',
        'query_builder' => [CallbackType::FUNCTION, 'getTestEntityQbCb'],
    ])]
    #[Assert\NotBlank]
    public ?TestEntity $testEntity = null;

    public function getTestEntityQbCb(): Closure
    {
        return function (EntityRepository $er) {
            return $er->createQueryBuilder('r')
                ->orderBy('r.name', 'ASC');
        };
    }
}
