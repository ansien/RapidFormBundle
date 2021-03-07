# RapidFormBundle - Create Symfony forms at record speed using PHP 8 attributes!

[comment]: <> (![GitHub Workflow Status &#40;branch&#41;]&#40;https://img.shields.io/github/workflow/status/ansien/RapidFormBundle/Tests/master?label=Tests&logo=Tests&#41;)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ansien/rapid-form-bundle.svg)](https://packagist.org/packages/ansien/rapid-form-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/ansien/rapid-form-bundle.svg)](https://packagist.org/packages/ansien/rapid-form-bundle)
![GitHub](https://img.shields.io/github/license/ansien/RapidFormBundle)

![Example](https://raw.githubusercontent.com/ansien/RapidFormBundle/master/.github/readme_example.png)

This goal of this bundle is to make it possible to build Symfony forms using [PHP 8 attributes](https://stitcher.io/blog/attributes-in-php-8) on your [DTO](https://blog.martinhujer.cz/symfony-forms-with-request-objects/).

#### The problem
Making forms in Symfony is fairly simple. But once you start using DTO's there will always be two classes you'll have to maintain: 
your DTO and your Symfony form type. This is not ideal because it creates unnecessary work, maintenance and can also easily lead to bugs.

#### The solution
This bundle will significantly speed up the creation of forms inside your Symfony application. With the provided PHP 8 
attributes you can quickly build forms by decorating your DTO and you won't have to maintain two different classes anymore.

## Installation
You can install the package via Composer:

```bash
composer require ansien/rapid-form-bundle
```

## Usage

#### Form
```php
<?php

declare(strict_types=1);

namespace App\Form;

use Ansien\RapidFormBundle\Attribute\Form;
use Ansien\RapidFormBundle\Attribute\FormField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[Form]
class ExampleForm
{
    #[FormField(TextType::class, [
        'required' => true,
    ])]
    #[Assert\NotBlank]
    public ?string $name = null;

    #[FormField(TextType::class)]
    public ?string $description = null;
}
```

#### Controller

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Ansien\RapidFormBundle\Form\RapidFormBuilderInterface;
use App\Form\ExampleForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    private RapidFormBuilderInterface $formBuilder;

    public function __construct(RapidFormBuilderInterface $formBuilder) {
        $this->formBuilder = $formBuilder;
    }

    #[Route('/example', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $data = new ExampleForm();
        $form = $this->formBuilder->create($data)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Do something with $data
        }
        
        return $this->render('example.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
```

See `./examples` for more examples.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Supporters
[![Stargazers repo roster for @ansien/RapidFormBundle](https://reporoster.com/stars/ansien/RapidFormBundle)](https://github.com/ansien/RapidFormBundle/stargazers)

## Credits
- [Andries](https://github.com/ansien)
- [Albert](https://github.com/abbert) (for his work on the deprecated annotated-form-bundle)
- [schvoy](https://github.com/schvoy) (for his work on the [form-annotation-bundle](https://github.com/eightmarq/form-annotation-bundle))
- [Bob](https://github.com/madebybob) and [Jon](https://github.com/jonmldr) (for their repository template)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
