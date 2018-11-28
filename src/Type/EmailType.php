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

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Utils\Utils;

/**
 * Represent email type
 *
 * @experimental
 */
class EmailType
{
    public static function create()
    {
        return new CustomScalarType([
            'name' => 'Email',
            'serialize' => [__CLASS__, 'serialize'],
            'parseValue' => [__CLASS__, 'parseValue'],
            'parseLiteral' => [__CLASS__, 'parseLiteral'],
        ]);
    }

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public static function serialize($value)
    {
        return self::parseValue($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public static function parseValue($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \UnexpectedValueException('Cannot represent value as email: ' . Utils::printSafe($value));
        }

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param Node $valueNode
     * @return string
     * @throws Error
     */
    public static function parseLiteral($valueNode)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }
        if (!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL)) {
            throw new Error('Not a valid email', [$valueNode]);
        }

        return $valueNode->value;
    }
}