<?php

declare(strict_types=1);

namespace Gedcom\Record\Head;

class Dest extends \Gedcom\Record
{
    protected $_dest;

    public function setDest($dest)
    {
        $this->_dest = $dest;
    }

    public function getDest()
    {
        return $this->_dest;
    }

    // Add more properties and methods for sub-tags if needed
}
