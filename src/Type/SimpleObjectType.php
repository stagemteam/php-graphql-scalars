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
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

/**
 * Represent simple PHP object
 *
 * @experimental
 */
class SimpleObjectType extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'SimpleObject';

    /**
     * @var string
     */
    public $description = 'The `SimpleObject` scalar type represents simple PHP object as Json';

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param object $value
     * @return object
     * @throws Error
     */
    public function serialize($value)
    {
        if (!is_object($value)) {
            throw new Error(sprintf('SimpleObject cannot represent non object value: %s', Utils::printSafe($value)));
        }

        if (method_exists($value, 'asArray')) {
            $value = $value->asArray();
        } elseif (method_exists($value, 'toArray')) {
            $value = $value->toArray();
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return object
     * @throws Error
     */
    public function parseValue($value)
    {
        #if (!is_object($value)) {
        #    throw new Error(sprintf('SimpleObject cannot represent non object value: %s', Utils::printSafe($value)));
        #}

        return (object) $value;
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
        if (!($valueNode instanceof ObjectValueNode)) {
            throw new Error('Query error: Can only parse simple objects got: ' . $valueNode->kind, [$valueNode]);
        }

        return $valueNode->fields;
    }
}


