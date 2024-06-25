<?php

namespace App;

class GedcomParser
{
    protected $individuals = [];

    protected $families = [];

    public function parse($filePath)
    {
        $lines         = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentEntity = null;
        $currentType   = null;

        foreach ($lines as $line) {
            $level = (int) substr($line, 0, 1);
            $parts = explode(' ', trim(substr($line, 2)), 2);
            $tag   = $parts[0];
            $data  = isset($parts[1]) ? $parts[1] : null;

            if ($level === 0) {
                if (strpos($tag, '@') !== false && strpos($data, 'INDI') !== false) {
                    $currentType                       = 'individual';
                    $currentEntity                     = $tag;
                    $this->individuals[$currentEntity] = [];
                } elseif (strpos($tag, '@') !== false && strpos($data, 'FAM') !== false) {
                    $currentType                    = 'family';
                    $currentEntity                  = $tag;
                    $this->families[$currentEntity] = [];
                }
            } else {
                if ($currentType === 'individual') {
                    if ($tag === 'CONC' || $tag === 'CONT') {
                        $lastIndex = count($this->individuals[$currentEntity]) - 1;
                        if ($tag === 'CONC') {
                            $this->individuals[$currentEntity][$lastIndex]['data'] .= $data;
                        } elseif ($tag === 'CONT') {
                            $this->individuals[$currentEntity][$lastIndex]['data'] .= '<br/>' . $data;
                        }
                    } else {
                        $this->individuals[$currentEntity][] = ['level' => $level, 'tag' => $tag, 'data' => $data];
                    }
                } elseif ($currentType === 'family') {
                    if ($tag === 'CONC' || $tag === 'CONT') {
                        $lastIndex = count($this->families[$currentEntity]) - 1;
                        if ($tag === 'CONC') {
                            $this->families[$currentEntity][$lastIndex]['data'] .= $data;
                        } elseif ($tag === 'CONT') {
                            $this->families[$currentEntity][$lastIndex]['data'] .= '<br/>' . $data;
                        }
                    } else {
                        $this->families[$currentEntity][] = ['level' => $level, 'tag' => $tag, 'data' => $data];
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
            echo '<h2>-------------------- INDIVIDUAL ' . $id . ' --------------------</h2>';

            echo '<div>';
            $this->outputTags($tags);
            echo '</div><br/>';
        }
    }

    public function outputFamilies()
    {
        foreach ($this->families as $id => $tags) {
            echo '<h2>-------------------- FAMILY ' . $id . ' --------------------</h2>';

            foreach ($tags as $tag) {
                if ($tag['tag'] === 'HUSB' || $tag['tag'] === 'WIFE' || $tag['tag'] === 'CHIL') {
                    echo "&emsp;{$tag['tag']}&nbsp;: {$tag['data']}<br>";
                }
            }
        }
    }

    protected function outputTags($tags, $baseLevel = 0)
    {
        foreach ($tags as $tag) {
            $indentation = str_repeat('&emsp;', $tag['level'] - $baseLevel);

            echo "{$indentation}{$tag['tag']}&nbsp;: {$tag['data']}<br>";
        }
    }
}
