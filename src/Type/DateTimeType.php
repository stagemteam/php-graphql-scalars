<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Stagem Team
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_GraphQL
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */
declare(strict_types=1);

namespace Stagem\GraphQL\Type;

use DateTime;
use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

/**
 * Represent native PHP DateTime
 *
 * @experimental
 */
class DateTimeType extends ScalarType
{
    /**
     * There is no const in PHP DateTime for correct support ISO 8601 format
     *
     * @see https://stackoverflow.com/a/48949373/1335142
     */
    const ISO8601 = 'Y-m-d\TH:i:s.uP';

    /**
     * @var string
     */
    public $name = 'DateTime';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents time data, represented as an ISO-8601 encoded UTC date string.';

    public function serialize($value)
    {
        if ($value instanceof DateTime) {
            return $value->format(DateTime::ATOM);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return DateTime|null
     */
    public function parseValue($value): ?DateTime
    {
        return DateTime::createFromFormat(self::ISO8601, $value) ?: null;
    }

    /**
     * @param Node $valueNode
     * @param array|null $variables
     * @return mixed|string
     * @throws Error
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!($valueNode instanceof StringValueNode)) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $valueNode->value;
    }
}


