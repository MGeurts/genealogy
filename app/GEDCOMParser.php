<?php

declare(strict_types=1);

namespace App;

class GEDCOMParser
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
            }

            if ($currentType === 'individual') {
                $this->individuals[$currentEntity][] = ['level' => $level, 'tag' => $tag, 'data' => $data];
            } elseif ($currentType === 'family') {
                $this->families[$currentEntity][] = ['level' => $level, 'tag' => $tag, 'data' => $data];
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

            $this->outputTags($tags);
        }
    }

    public function outputFamilies()
    {
        foreach ($this->families as $id => $tags) {
            echo "-------------------- FAMILY " . $id . " --------------------<br/>";

            foreach ($tags as $tag) {
                if ($tag['tag'] === 'HUSB' || $tag['tag'] === 'WIFE' || $tag['tag'] === 'CHIL') {
                    echo "{$tag['tag']}&nbsp;: {$tag['data']}<br/>";
                }
            }
        }
    }

    protected function outputTags($tags, $level = 0)
    {
        foreach ($tags as $tag) {
            if ($tag['level'] === $level) {
                echo str_repeat('&emsp;', $level) . "{$tag['tag']}&nbsp;: {$tag['data']}<br/>";
                $this->outputTags($tags, $level + 1);
            }
        }
    }
}
