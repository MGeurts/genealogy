<?php

declare(strict_types=1);

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom;

use Exception;

/**
 * Base class for various record types in the GEDCOM project.
 *
 * Provides common functionality such as ID and name handling for all record types.
 */
abstract class Record implements Models\RecordInterface
{
    private $_id;

    private $_name;

    public function __call($method, $args)
    {
        if (str_starts_with((string) $method, 'add')) {
            $arr = mb_strtolower(mb_substr((string) $method, 3));

            if (! property_exists($this, '_' . $arr) || ! is_array($this->{'_' . $arr})) {
                throw new Exception('Unknown ' . static::class . '::' . $arr);
            }

            if (! is_array($args)) {
                throw new Exception('Incorrect arguments to ' . $method);
            }

            if (! isset($args[0])) {
                // Argument can be empty since we trim it's value
                return;
                throw new Exception('Unknown ' . static::class . '::' . $arr);
            }

            if (! is_array($args)) {
                throw new Exception('Incorrect arguments to ' . $method);
            }

            if (! isset($args[0])) {
                // Argument can be empty since we trim it's value
                return;
            }

            if (is_object($args[0])) {
                // Type safety?
            }

            $this->{'_' . $arr}[] = $args[0];

            return $this;
        }
        if (str_starts_with((string) $method, 'set')) {
            $arr = mb_strtolower(mb_substr((string) $method, 3));

            if (! property_exists($this, '_' . $arr)) {
                throw new Exception('Unknown ' . static::class . '::' . $arr);
            }

            if (! is_array($args)) {
                throw new Exception('Incorrect arguments to ' . $method);
            }

            if (! isset($args[0])) {
                // Argument can be empty since we trim it's value
                return;
            }

            if (is_object($args[0])) {
                // Type safety?
            }

            $this->{'_' . $arr} = $args[0];

            return $this;
        }
        if (str_starts_with((string) $method, 'get')) {
            $arr = mb_strtolower(mb_substr($method, 3));

            if (! property_exists($this, '_' . $arr)) {
                throw new Exception('Unknown ' . get_class($this) . '::' . $arr);
            }

            return $this->{'_' . $arr};
        }
        throw new Exception('Unknown method called: ' . $method);
    }

    /**
     * Magic method to prevent setting of undefined properties.
     *
     * @param  string  $var  The name of the property being set.
     * @param  mixed  $val  The value being assigned to the property.
     *
     * @throws Exception Always thrown to indicate an undefined property.
     */
    public function __set($var, $val)
    {
        // this class does not have any public vars
        throw new Exception('Undefined property ' . self::class . '::' . $var);
    }

    /**
     * Retrieves the ID of the record.
     *
     * @return mixed|null The ID of the record, or null if not set.
     */
    public function getId()
    {
        return $this->_id ?? null;
    }

    /**
     * Sets the ID of the record.
     *
     * @param  mixed  $id  The new ID of the record.
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Retrieves the name of the record.
     *
     * @return string|null The name of the record, or null if not set.
     */
    public function getName()
    {
        return $this->_name ?? null;
    }

    /**
     * Sets the name of the record.
     *
     * @param  string  $name  The new name of the record.
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Checks if this GEDCOM object has the provided attribute (ie, if the provided
     * attribute exists below the current object in its tree).
     *
     * @param  string  $var  The name of the attribute
     * @return bool True if this object has the provided attribute
     */
    public function hasAttribute($var)
    {
        return property_exists($this, '_' . $var) || property_exists($this, $var);
    }
}
