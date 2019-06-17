PHP GraphQL Scalars
===========

Custom GraphQL Scalars for creating precise type-safe GraphQL schemas.


Requirements
------------

-   PHP 7.0+
-   [PHP Webonyx GraphQL](https://github.com/webonyx/graphql-php)


Installation
------------

-   using [composer](http://getcomposer.org): `composer require stagem/php-graphql-scalars`

-   using other [PSR-4](http://www.php-fig.org/psr/psr-4/) compliant autoloader:
    clone this project to where your included libraries are and point your autoloader to look for the 
    "\Stagem\GraphQL" namespace in the "src" directory of this project


Available types
------------

-   DateType
-   DateTimeType
-   IterableType
-   JsonType
-   EmailType
-   SimpleObjectType


### DateType
A date string, such as 2012-12-31, compliant with the full-date format outlined in section 5.6 of the [RFC 3339](https://github.com/excitement-engineer/graphql-iso-date/blob/master/rfc3339.txt) profile of the ISO 8601 standard for representation of dates and times using the Gregorian calendar.

This scalar is a description of the date, as used for birthdays for example. It cannot represent an instant on the time-line.

### DateTimeType
The `DateTime` scalar type represents time data, represented as an [ISO-8601](https://en.wikipedia.org/wiki/ISO_8601) encoded UTC date string.

#### Result Coercion
PHP `DateTime` instances are coerced to an `DateTime::ATOM` (RFC 3339) compliant date string.

#### Input Coercion
When expected as an input type, most of valid ISO-8601 compliant date strings are accepted.

All next formats are valid and will be successfully parsed:
- "2010-12-07T23:00:00.000Z"
- "2010-12-07T23:00:00"
- "2010-12-07T23:00:00Z"
- "2010-12-07T23:00:00+01:00"

### IterableType & JsonType
Any valid JSON format.

### SimpleObjectType
Any valid PHP object which can be converted to array. 
Additionally support `asArray` and `toArray` methods for conversion.


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




