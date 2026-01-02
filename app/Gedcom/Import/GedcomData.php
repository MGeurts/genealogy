<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

/**
 * Data Transfer Object for parsed GEDCOM data
 */
class GedcomData
{
    /** @var array<array{type: string, value: string}> */
    private array $gedcomData = [];

    /** @var array<string, array{id: string, type: string, data: array<mixed>}> */
    private array $individuals = [];

    /** @var array<string, array{id: string, type: string, data: array<mixed>}> */
    private array $families = [];

    /**
     * @param  array<array{type: string, value: string}>  $gedcomData
     */
    public function setGedcomData(array $gedcomData): void
    {
        $this->gedcomData = $gedcomData;
    }

    /**
     * @param  array<string, array{id: string, type: string, data: array<mixed>}>  $individuals
     */
    public function setIndividuals(array $individuals): void
    {
        $this->individuals = $individuals;
    }

    /**
     * @param  array<string, array{id: string, type: string, data: array<mixed>}>  $families
     */
    public function setFamilies(array $families): void
    {
        $this->families = $families;
    }

    /**
     * @return array<array{type: string, value: string}>
     */
    public function getGedcomData(): array
    {
        return $this->gedcomData;
    }

    /**
     * @return array<string, array{id: string, type: string, data: array<mixed>}>
     */
    public function getIndividuals(): array
    {
        return $this->individuals;
    }

    /**
     * @return array<string, array{id: string, type: string, data: array<mixed>}>
     */
    public function getFamilies(): array
    {
        return $this->families;
    }
}
