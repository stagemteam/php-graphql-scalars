<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2019 Serhii Popov
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
use DateTimeInterface;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class TimeType extends ScalarType
{
    const TIME_FORMAT = 'H:i:s';
    /**
     * @var string
     */
    public $name = 'Time';

    /**
     * @var string
     */
    public $description = 'The `Time` scalar type represents time data in format "15:20:32"';

    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     * @throws Error If the provided value does not implement DateTimeInterface.
     */
    public function serialize($value)
    {
        if (!($value instanceof DateTimeInterface)) {
            throw new Error(sprintf('Date cannot represent non DateTime value: %s', Utils::printSafe($value)));
        }

        return $value->format(self::TIME_FORMAT);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     * In the case of an invalid value this method must throw an Exception
     *
     * @param mixed $value
     * @return mixed
     * @throws Error
     */
    public function parseValue($value): ?DateTime
    {
        $dt = DateTime::createFromFormat(self::TIME_FORMAT, $value);

        return $dt ? $dt->setTime(0, 0) : null;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node $valueNode
     * @param array|null $variables
     * @return mixed
     * @throws \Exception
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        if (!($valueNode instanceof StringValueNode)) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $valueNode->value;
    }
}
