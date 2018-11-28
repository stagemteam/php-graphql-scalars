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

/**
 * Alias of IterableType
 *
 * @experimental
 */
class JsonType extends IterableType
{
    /**
     * @var string
     */
    public $name = 'Json';

    /**
     * @var string
     */
    public $description =
        'The `JSON` scalar type represents JSON values as specified by
        [ECMA-404](http://www.ecma-international.org/publications/files/ECMA-ST/ECMA-404.pdf).';
}