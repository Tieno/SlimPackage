<?php

/*
 * This file is part of Twig.
 *
 * (c) 2012 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a template context.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Context
{
    protected $variables;

    public function __construct(array $variables = array())
    {
        $this->variables = $variables;
    }

    /**
     * don't use it
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
