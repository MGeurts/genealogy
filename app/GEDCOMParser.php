<?php

namespace App;

class GedcomParser
{
    protected $individuals = [];
    protected $families = [];

    public function parse($filePath)
    {
        if (!file_exists($filePath)) {
            echo "File not found: $filePath<br/>";
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentEntity = null;
        $currentType = null;

        foreach ($lines as $line) {
            $level = (int)substr($line, 0, 1);
            $parts = explode(' ', trim(substr($line, 2)), 2);
            $tag = $parts[0];
            $data = isset($parts[1]) ? $parts[1] : null;

            if ($level === 0) {
                if (strpos($tag, '@') !== false && strpos($data, 'INDI') !== false) {
                    $currentType = 'individual';
                    $currentEntity = $tag;
                    $this->individuals[$currentEntity] = [];
                } elseif (strpos($tag, '@') !== false && strpos($data, 'FAM') !== false) {
                    $currentType = 'family';
                    $currentEntity = $tag;
                    $this->families[$currentEntity] = [];
                }
            } else {
                if ($currentType === 'individual') {
                    if (!isset($this->individuals[$currentEntity][$tag])) {
                        $this->individuals[$currentEntity][$tag] = $data;
                    } else {
                        $this->individuals[$currentEntity][$tag] .= "<br/>" . $data;
                    }
                } elseif ($currentType === 'family') {
                    if (!isset($this->families[$currentEntity][$tag])) {
                        $this->families[$currentEntity][$tag] = $data;
                    } else {
                        $this->families[$currentEntity][$tag] .= "<br/>" . $data;
                    }
                }
            }
        }
    }

    public function getIndividuals()
    {
        return $this->individuals;
    }

    public function getFamilies()
    {
        return $this->families;
    }

    public function outputIndividuals()
    {
        foreach ($this->individuals as $id => $tags) {
            echo "-------------------- INDIVIDUAL " . $id . " --------------------<br/>";

            foreach ($tags as $tag => $data) {
                echo "  $tag: $data<br/>";
            }
            echo "<br/>";
        }
    }

    public function outputFamilies()
    {
        foreach ($this->families as $id => $tags) {
            echo "-------------------- FAMILY " . $id . " --------------------<br/>";

            foreach ($tags as $tag => $data) {
                if ($tag === 'HUSB' || $tag === 'WIFE' || $tag === 'CHIL') {
                    echo "  $tag: $data<br/>";
                }
            }
            echo "<br/>";
        }
    }
}
