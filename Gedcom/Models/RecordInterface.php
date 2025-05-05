<?php

declare(strict_types=1);

namespace Gedcom\Models;

/**
 * Interface for GEDCOM record models.
 *
 * Defines the essential operations that all GEDCOM record types must implement,
 * such as getting and setting the ID and name.
 */
interface RecordInterface
{
    /**
     * Retrieves the ID of the record.
     *
     * @return mixed The ID of the record.
     */
    public function getId();

    /**
     * Sets the ID of the record.
     *
     * @param  mixed  $id  The new ID of the record.
     */
    public function setId($id);

    /**
     * Retrieves the name of the record.
     *
     * @return string The name of the record.
     */
    public function getName();

    /**
     * Sets the name of the record.
     *
     * @param  string  $name  The new name of the record.
     */
    public function setName($name);
}
