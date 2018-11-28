PHP GraphQL Scalars
===========

Custom GraphQL Scalars for creating precise type-safe GraphQL schemas.


Requirements
------------

-   PHP 7.0+
-   [PHP GraphQL](https://github.com/webonyx/graphql-php)

Installation
------------

-   using [composer] (http://getcomposer.org): `composer require stagem/php-graphql-scalars`

-   using other [PSR-4] (http://www.php-fig.org/psr/psr-4/) compliant autoloader:
    clone this project to where your included libraries are and point your autoloader to look for the 
    "\Stagem\GraphQL" namespace in the "src" directory of this project

Available types
------------

-   DateTimeType
-   IterableType (alias JsonType)
-   EmailType

Usage
-----

Simply create `DateTimeType` and use it in your GraphQl Schema.

> Remember any type must be unique.

```php
<?php
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Stagem\GraphQL\Type\DateTimeType;

$dateTimeType = new DateTimeType();

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echoDate' => [
            'type' => Type::string(),
            'args' => [
                'date' => ['type' => $dateTimeType],
            ],
            'resolve' => function ($root, $args) {
                return $args['date'];
            }
        ],
    ],
]);
```


**Advanced usage**
It is good practice use a [PSR-11](https://www.php-fig.org/psr/psr-11/) container for getting types.
In the following example, we use [zendframework/zend-servicemanager](https://github.com/zendframework/zend-servicemanager), 
because it offers useful concepts such as: invokables, aliases, factories and abstract factories. 
But any other PSR-11 container implementation could be used instead.

The keys should be the whatever you use to refer to the type in your model. 
Typically that would be either the FQCN of a PHP class "native" type such as DateTime, 
or the FQCN of a PHP class implementing the GraphQL type, 
or directly the GraphQL type name:

```php
<?php
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Stagem\GraphQL\Type\DateTimeType;
use Stagem\GraphQL\Type\JsonType;

$customTypes = new \Zend\ServiceManager\ServiceManager([
    'invokables' => [
        DateTime::class => DateTimeType::class,
        'Json' => JsonType::class,
    ],
]);

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echoDate' => [
            'type' => Type::string(),
            'args' => [
                'date' => ['type' => $customTypes->get(DateTime::class)],
            ],
            'resolve' => function ($root, $args) {
                return $args['date'];
            }
        ],
    ],
]);
```

That way it is not necessary to annotate every single getter returning one of the configured type. 
It will be mapped automatically.



Contributing
-----------

Please feel free to report bugs or request features using the [Issues tab](https://github.com/stagemteam/php-graphql-scalars/issues). 
If you'd like to contribute, feel free to fork our repository and send a pull request with your modifications. 
Let's make it better together!


History
-------

When we started use GraphGL it was strange that nobody create library with useful custom GraphQL types.

Googling around we found many questions with proposals (for example [one](https://github.com/webonyx/graphql-php/issues/129), [two](https://github.com/webonyx/graphql-php/issues/228))
to include such types in `GraphQL` but in all cases it was rejected.

Other libraries create own internal types just copying it from each other.

After that, we have decided to correct this injustice and created this library that every one can use it without copy-past.

Our inspiration was taken from [okgrow/graphql-scalars](https://github.com/okgrow/graphql-scalars) but it is not port
of that library.




