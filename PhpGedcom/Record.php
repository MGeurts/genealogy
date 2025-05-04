<?php

/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @package         php-gedcom
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom;

/**
 *
 */
abstract class Record
{
    /**
     *
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) == 'add') {
            $arr = strtolower(substr($method, 3));

            if (!property_exists($this, '_' . $arr) || !is_array($this->{'_' . $arr})) {
                throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
            }

            if (!is_array($args) || !isset($args[0])) {
                throw new \Exception('Incorrect arguments to ' . $method);
            }

            if (is_object($args[0])) {
                // Type safety?
            }

            $this->{'_' . $arr}[] = $args[0];

            return $this;
        } elseif (substr($method, 0, 3) == 'set') {
            $arr = strtolower(substr($method, 3));

            if (!property_exists($this, '_' . $arr)) {
                throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
            }

            if (!is_array($args) || !isset($args[0])) {
                throw new \Exception('Incorrect arguments to ' . $method);
            }

            if (is_object($args[0])) {
                // Type safety?
            }

            $this->{'_' . $arr} = $args[0];

            return $this;
        } elseif (substr($method, 0, 3) == 'get') {
            $arr = strtolower(substr($method, 3));

            if (!property_exists($this, '_' . $arr)) {
                throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
            }

            return $this->{'_' . $arr};
        } else {
            throw new \Exception('Unknown method called: ' . $method);
        }
    }

    /**
     *
     */
    public function __set($var, $val)
    {
        // this class does not have any public vars
        throw new \Exception('Undefined property ' . get_class() . '::' . $var);
    }

    /**
     * Checks if this GEDCOM object has the provided attribute (ie, if the provided
     * attribute exists below the current object in its tree).
     *
     * @param string $var The name of the attribute
     * @return bool True if this object has the provided attribute
     */
    public function hasAttribute($var)
    {
        return property_exists($this, '_' . $var) || property_Exists($this, $var);
    }
}
