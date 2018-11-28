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
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\ValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

/**
 * Represents an iterable type
 *
 * @experimental
 */
class IterableType extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'Iterable';

    /**
     * @var string
     */
    public $description = 'The `Iterable` scalar type represents an array or a Traversable with any kind of data.';

    /**
     * {@inheritdoc}
     */
    public function serialize($value)
    {
        // is_iterable
        if (!(\is_array($value) || $value instanceof \Traversable)) {
            throw new Error(sprintf('Iterable cannot represent non iterable value: %s', Utils::printSafe($value)));
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function parseValue($value)
    {
        // is_iterable
        if (!(\is_array($value) || $value instanceof \Traversable)) {
            throw new Error(sprintf('Iterable cannot represent non iterable value: %s', Utils::printSafeJson($value)));
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        if ($valueNode instanceof ObjectValueNode || $valueNode instanceof ListValueNode) {
            return $this->parseIterableLiteral($valueNode);
        }
        // Intentionally without message, as all information already in wrapped Exception
        throw new \Exception();
    }

    /**
     * @param StringValueNode|BooleanValueNode|IntValueNode|FloatValueNode|ObjectValueNode|ListValueNode|ValueNode $valueNode
     */
    private function parseIterableLiteral($valueNode)
    {
        switch ($valueNode) {
            case $valueNode instanceof StringValueNode:
            case $valueNode instanceof BooleanValueNode:
                return $valueNode->value;
            case $valueNode instanceof IntValueNode:
                return (int) $valueNode->value;
            case $valueNode instanceof FloatValueNode:
                return (float) $valueNode->value;
            case $valueNode instanceof ObjectValueNode:
                $value = [];
                foreach ($valueNode->fields as $field) {
                    $value[$field->name->value] = $this->parseIterableLiteral($field->value);
                }

                return $value;
            case $valueNode instanceof ListValueNode:
                $list = [];
                foreach ($valueNode->values as $value) {
                    $list[] = $this->parseIterableLiteral($value);
                }

                return $list;
            default:
                return null;
        }
    }
}