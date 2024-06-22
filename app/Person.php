<?php

namespace App;

class Person
{
    public static $_gedcom;

    public static $_people;

    public static $generationsToName = 10;

    public $_data;

    public $_father;

    public $_mother;

    public $_siblings;

    public $_children;

    public $_spouses;

    public $_parents = [];

    public $_ancestorIDs = null;

    public function __construct($id = null, $gedcom = null)
    {
        if ($gedcom === null) {
            $gedcom = self::$_gedcom;
        }
        if (! $id) {
            $this->_data = [];

            return;
        }
        if (is_array($id)) {
            $id = self::_id($id);
        }
        if (isset($gedcom['INDI'][$id])) {
            $this->_data = $gedcom['INDI'][$id];

            return;
        }
    }

    public static function singleton($data = null)
    {
        if (! isset(self::$_people)) {
            self::$_people = [];
        }
        $id = self::_id($data);
        if (! $id) {
            $id = -1;
        }
        if (! isset(self::$_people[$id])) {
            $class              = __CLASS__;
            self::$_people[$id] = new $class($id);
        }

        return self::$_people[$id];
    }

    public static function parse($file)
    {
        $gedcom = [];
        $file   = fopen($file, 'r');
        $id     = null;
        while (! feof($file)) {
            $line = trim(fgets($file));
            if (preg_match('/^0 @([A-Z0-9]+)@ (\w*)/', $line, $match)) {
                $id   = $match[1];
                $type = $match[2];
                if (! isset($gedcom[$type])) {
                    $gedcom[$type] = [];
                }
                if (! isset($gedcom[$type][$id])) {
                    $gedcom[$type][$id] = ['_ID' => $id];
                }
            } elseif ($id && preg_match('/^(\d+)\s+(\w+)\s*(.*)/', $line, $match)) {
                $num  = $match[1] / 1;
                $tag  = $match[2];
                $data = $match[3];
                if ($num == 1) {
                    $masterTag = $tag;
                    if (! isset($gedcom[$type][$id][$masterTag])) {
                        $gedcom[$type][$id][$masterTag] = [];
                    }
                    if (! isset($gedcom[$type][$id][$masterTag][$tag])) {
                        $gedcom[$type][$id][$masterTag][$tag] = [];
                    }
                    array_push($gedcom[$type][$id][$masterTag][$tag], $data);
                } elseif ($num == 2) {
                    if (! isset($gedcom[$type][$id][$masterTag][$tag])) {
                        $gedcom[$type][$id][$masterTag][$tag] = [];
                    }
                    array_push($gedcom[$type][$id][$masterTag][$tag], $data);
                }
                // if (!isset($gedcom[$type][$id]['GEDCOM'])) $gedcom[$type][$id]['GEDCOM'] = '';
                // $gedcom[$type][$id]['GEDCOM'] .= $line . "\n";
            }
        }
        fclose($file);
        self::$_gedcom = $gedcom;

        return $gedcom;
    }

    public static function _id($data)
    {
        if (! $data) {
            return null;
        }
        if (is_array($data)) {
            if (isset($data['_ID'])) {
                return self::_id($data['_ID']);
            }
            if (isset($data[0])) {
                return self::_id($data[0]);
            }
        }

        return @preg_replace('/@/', '', $data);
    }

    public function data($tag)
    {
        if (! isset($this->_data[$tag])) {
            return null;
        }

        return $this->_data[$tag];
    }

    public function id()
    {
        return substr($this->_data['_ID'], 1);
    }

    public function gender()
    {
        if (! isset($this->_data['SEX'])) {
            return null;
        }

        return $this->_data['SEX']['SEX'][0];
    }

    public function childType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('son');
        }
        if ($this->gender() == 'F') {
            return self::i18n('daughter');
        }

        return self::i18n('child');
    }

    public function grandChildType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('grandson');
        }
        if ($this->gender() == 'F') {
            return self::i18n('granddaughter');
        }

        return self::i18n('grandchild');
    }

    public function siblingType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('brother');
        }
        if ($this->gender() == 'F') {
            return self::i18n('sister');
        }

        return self::i18n('sibling');
    }

    public function parentSiblingType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('uncle');
        }
        if ($this->gender() == 'F') {
            return self::i18n('aunt');
        }
    }

    public function siblingChildType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('nephew');
        }
        if ($this->gender() == 'F') {
            return self::i18n('niece');
        }
    }

    public function parentType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('father');
        }
        if ($this->gender() == 'F') {
            return self::i18n('mother');
        }

        return self::i18n('parent');
    }

    public function grandParentType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('grandfather');
        }
        if ($this->gender() == 'F') {
            return self::i18n('grandmother');
        }

        return self::i18n('grandparent');
    }

    public function greatGrandParentType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('great-grandfather');
        }
        if ($this->gender() == 'F') {
            return self::i18n('great-grandmother');
        }

        return self::i18n('great-grandparent');
    }

    public function greatGrandChildType()
    {
        if ($this->gender() == 'M') {
            return self::i18n('great-grandson');
        }
        if ($this->gender() == 'F') {
            return self::i18n('great-granddaughter');
        }

        return self::i18n('great-grandchild');
    }

    public function _urlise($name)
    {
        $find    = ['/\s+/', '/[^\w\'+()-]/', '/\'/'];
        $replace = ['+', '', '%27'];

        return strtolower(preg_replace($find, $replace, trim($name)));
    }

    public function _partOfName($part = 'NAME', $link = false, $years = false, $schema = false)
    {
        if (! isset($this->_data['NAME'][$part])) {
            return 'unknown';
        }
        $name = trim(preg_replace('/\//', '', $this->_data['NAME'][$part][0]));
        if (! $link) {
            if ($years) {
                return $name . ' ' . $this->years();
            }

            return $name;
        }
        $html = '<a href="' . $this->link() . '"';
        if ($schema) {
            $html .= ' itemprop="url sameAs"';
        }
        $html .= '>';
        if ($schema) {
            $html .= '<span itemprop="name">';
            $html .= $name;
            $html .= '</span>';
        } else {
            $html .= $name;
        }
        if ($years) {
            $html .= ' ' . $this->years($schema);
        }
        $html .= '</a>';

        return $html;
    }

    /**
     * need to override this function when you initialise
     * for example:
     *   $person->link = function () { return 'http://foo.com/tree/' + $this->id(); };
     *   $person->link = function () { return 'http://foo.com/search?name=' + $this->_urlise($this->surname()); };
     * etc
     * I'll come back to this...
     */
    public function link()
    {
        return 'http://www.clarkeology.com/names/' . $this->_urlise($this->surname()) . '/' . $this->id() . '/' . $this->_urlise($this->forename());
        // return 'http://www.clarkeology.com/wiki/' . $this->_urlise($this->name());
    }

    public function name($link = false, $years = false, $schema = false)
    {
        if ($this->isPrivate()) {
            return '[PRIVATE]';
        }

        return $this->_partOfName('NAME', $link, $years, $schema);
    }

    public function forename()
    {
        return substr($this->name(), 0, stripos($this->name(), $this->surname()) - 1);
    }

    public function surname($link = false)
    {
        return $this->_partOfName('SURN', $link);
    }

    public function occupation()
    {
        if (! isset($this->_data['OCCU'])) {
            return null;
        }

        return $this->tagToLabel('OCCU') . ' ' . implode(', ', array_unique($this->_data['OCCU']['OCCU']));
    }

    public function source($ids = [])
    {
        $sources = [];
        foreach ($ids as $id) {
            $id = $this->_id($id);
            if (! isset(self::$_gedcom['SOUR'][$id])) {
                continue;
            }
            foreach (['_TYPE', 'TEXT'] as $tag) {
                if (isset(self::$_gedcom['SOUR'][$id][$tag])) {
                    $source = '';
                    if (isset(self::$_gedcom['SOUR'][$id][$tag][$tag])) {
                        $source .= implode('', self::$_gedcom['SOUR'][$id][$tag][$tag]);
                    }
                    if (isset(self::$_gedcom['SOUR'][$id][$tag]['CONC'])) {
                        $source .= implode('', self::$_gedcom['SOUR'][$id][$tag]['CONC']);
                    }
                    array_push($sources, $source);
                }
            }
        }

        return implode(', ', $sources);
    }

    public function note($ids = [])
    {
        $notes = [];
        foreach ($ids as $id) {
            if ($id = $this->_id($id)) {
                foreach (['CONC'] as $tag) {
                    if (isset(self::$_gedcom['NOTE'][$id][$tag])) {
                        foreach (self::$_gedcom['NOTE'][$id][$tag][$tag] as $note) {
                            array_push($notes, $note);
                        }
                    } else {
                        array_push($notes, $id);
                    }
                }
            }
        }

        return implode('', $notes);
    }

    public function notes()
    {
        if (! isset($this->_data['NOTE'])) {
            return null;
        }
        $notes = [];
        foreach ($this->_data['NOTE']['NOTE'] as $id) {
            $id = $this->_id($id);
            if (isset(self::$_gedcom['NOTE'][$id]['CONT'])) {
                $text = '';
                foreach (self::$_gedcom['NOTE'][$id]['CONT']['CONT'] as $key => $note) {
                    $text .= '<br /> ' . $note;
                    if (isset(self::$_gedcom['NOTE'][$id]['CONC'])) {
                        if (isset(self::$_gedcom['NOTE'][$id]['CONC']['CONC'])) {
                            if (isset(self::$_gedcom['NOTE'][$id]['CONC']['CONC'][$key])) {
                                $text .= self::$_gedcom['NOTE'][$id]['CONC']['CONC'][$key];
                            }
                        }
                    }
                }
                array_push($notes, $text);
            } else {
                if (isset(self::$_gedcom['NOTE'][$id]['CONC'])) {
                    array_push($notes, implode(',', self::$_gedcom['NOTE'][$id]['CONC']['CONC']));
                } else {
                    error_log('hmm no CONT or CONC in ' . json_encode(self::$_gedcom['NOTE'][$id]));
                }
            }
        }

        return implode('<br />', $notes);
    }

    public function will()
    {
        $year = $this->_year('DEAT');
        if (! $year) {
            return;
        }
        if ($year < 1858) {
            return;
        }

        return '<a href="https://probatesearch.service.gov.uk/Calendar?surname=' . $this->_urlise($this->surname()) . '&yearOfDeath=' . $year . '&page=1#calendar">' . $this->name() . '\'s will</a>';
    }

    public function familiesWithParents()
    {
        if (! isset($this->_data['FAMC'])) {
            return null;
        }
        $familyID = $this->_id($this->_data['FAMC']['FAMC'][0]);

        return self::$_gedcom['FAM'][$familyID];
    }

    public function familiesWithSpouse()
    {
        if (! isset($this->_data['FAMS'])) {
            return [];
        }
        $families = [];
        foreach ($this->_data['FAMS']['FAMS'] as $family) {
            $familyID = $this->_id($family);
            array_push($families, self::$_gedcom['FAM'][$familyID]);
        }

        return $families;
    }

    public function mother()
    {
        if (! $this->_mother) {
            $family        = $this->familiesWithParents();
            $mother        = isset($family['WIFE']) ? $family['WIFE']['WIFE'][0] : null;
            $this->_mother = self::singleton($mother);
        }

        return $this->_mother;
    }

    public function father()
    {
        if (! $this->_father) {
            $family        = $this->familiesWithParents();
            $father        = isset($family['HUSB']) ? $family['HUSB']['HUSB'][0] : null;
            $this->_father = self::singleton($father);
        }

        return $this->_father;
    }

    public function spouses()
    {
        if (! $this->_spouses) {
            $this->_spouses = [];
            foreach ($this->familiesWithSpouse() as $family) {
                foreach (['WIFE', 'HUSB'] as $tag) {
                    if (isset($family[$tag])) {
                        foreach ($family[$tag][$tag] as $spouse) {
                            $spouse = self::singleton($spouse);
                            if ($spouse->id() != $this->id()) {
                                array_push($this->_spouses, $spouse);
                            }
                        }
                    }
                }
            }
        }

        return $this->_spouses;
    }

    public function siblings()
    {
        if (! $this->_siblings) {
            $this->_siblings = [];
            $family          = $this->familiesWithParents();
            if (isset($family['CHIL'])) {
                foreach ($family['CHIL']['CHIL'] as $child) {
                    $sibling = self::singleton($child);
                    if ($sibling->id() != $this->id()) {
                        array_push($this->_siblings, $sibling);
                    }
                }
            }
        }

        return $this->_siblings;
    }

    public function children()
    {
        if (! $this->_children) {
            $this->_children = [];
            foreach ($this->familiesWithSpouse() as $family) {
                if (isset($family['CHIL'])) {
                    foreach ($family['CHIL']['CHIL'] as $child) {
                        array_push($this->_children, self::singleton($child));
                    }
                }
            }
        }

        return $this->_children;
    }

    public function _place($type = 'BIRT', $itemProp = false, $default = null)
    {
        if (! isset($this->_data[$type])) {
            return $default;
        }
        if (! isset($this->_data[$type]['PLAC'])) {
            return $default;
        }
        if (! isset($this->_data[$type]['PLAC'][0])) {
            return $default;
        }
        if (! $itemProp) {
            return $this->_data[$type]['PLAC'][0];
        }

        return '<meta itemprop="' . $itemProp . '" content="' . $this->_data[$type]['PLAC'][0] . '" />';
    }

    public function _year($type = 'BIRT', $schema = false)
    {
        if (! isset($this->_data[$type])) {
            return '';
        }
        if (! isset($this->_data[$type]['DATE'])) {
            return '';
        }
        $time = strtotime($this->_data[$type]['DATE'][0]);
        $date = $time ? date('Y-m-d', $time) : $this->_data[$type]['DATE'][0];
        if ($date == date('Y-m-d')) {
            $date = $this->_data[$type]['DATE'][0];
        }
        // error_log(__METHOD__ . ' ' . $this->_data[$type]['DATE'][0] . ' ' . $time . ' ' . $date);
        if (preg_match('/(\d{4})(-(\d\d)-(\d\d))?/', $date, $match)) {
            // error_log(__METHOD__ . ' ' . json_encode($match));
            if ($schema && ($type == 'BIRT' || $type == 'DEAT')) {
                $itemprop = $type == 'BIRT' ? 'birthDate' : 'deathDate';
                // $html = '<span itemprop="' . $itemprop . '" content="' . $date . '">' . $match[1] . '</span>';
                $html = '<time itemprop="' . $itemprop . '" datetime="' . $date . '">' . $match[1] . '</time>';

                // error_log($html);
                return $html;
            }

            return $match[1];
        }

        return '';
    }

    public function _date($type = 'BIRT')
    {
        if (! isset($this->_data[$type])) {
            return 'unknown';
        }
        if (isset($this->_data[$type]['DATE'])) {
            return $this->_data[$type]['DATE'][0];
        }
        if (isset($this->_data[$type]['PLAC'])) {
            return $this->_data[$type]['PLAC'][0];
        }

        return 'unknown';
    }

    public function dates()
    {
        if (! isset($this->_data['DEAT'])) {
            return '';
        }

        return '(' . $this->_date('BIRT') . ' - ' . $this->_date('DEAT') . ')';
    }

    public function years($schema = false)
    {
        $birth = $this->_year('BIRT');
        $death = $this->_year('DEAT');
        if (! ($birth || $death)) {
            return '';
        }

        return $this->_year('BIRT', $schema) . ' - ' . $this->_year('DEAT', $schema);
    }

    public function tagToLabel($tag)
    {
        if ($tag == 'BIRT') {
            return ucfirst(self::i18n('born'));
        }
        if ($tag == 'BAPM') {
            return ucfirst(self::i18n('baptised'));
        }
        if ($tag == 'DEAT') {
            return ucfirst(self::i18n('died'));
        }
        if ($tag == 'BURI') {
            return ucfirst(self::i18n('buried'));
        }
        if ($tag == 'OCCU') {
            return ucfirst(self::i18n('occupation'));
        }

        return $tag;
    }

    public function timeAndPlace($tag)
    {
        $label = $this->tagToLabel($tag);
        if (! isset($this->_data[$tag])) {
            return null;
        }
        $return = [ucfirst($label)];
        if (isset($this->_data[$tag]['DATE'])) {
            array_push($return, $this->_data[$tag]['DATE'][0]);
        }
        if (isset($this->_data[$tag]['PLAC'])) {
            array_push($return, $this->_data[$tag]['PLAC'][0]);
        }
        // if (isset($this->_data[$tag]['NOTE'])) array_push($return, '(' . $this->_data[$tag]['NOTE'][0] . ')');
        if (isset($this->_data[$tag]['NOTE'])) {
            array_push($return, '(' . $this->note($this->_data[$tag]['NOTE']) . ')');
        }
        if (isset($this->_data[$tag]['SOUR'])) {
            array_push($return, '(source: ' . $this->source($this->_data[$tag]['SOUR']) . ')');
        }

        return implode(' ', $return);
    }

    public function td($person, $schemaRelationship, $columns, $class = null)
    {
        $html = '<td colspan="' . $columns . '"';
        if ($class) {
            $html .= ' class="' . $class . '"';
        }
        if (! $person->id() || $person->isPrivate()) {
            $schemaRelationship = null;
        }
        if ($schemaRelationship) {
            $html .= ' itemprop="' . $schemaRelationship . '" itemscope itemtype="http://schema.org/Person"';
        }
        $html .= '>';
        $html .= $person->name(true, $schemaRelationship, $schemaRelationship);
        $html .= '</td>';

        return $html;
    }

    public function tableTree($schema = false)
    {
        $html = '<table class="family"';
        // $html .= ' summary="' . $this->name() . ' family tree"';
        if ($schema) {
            $html .= ' itemscope itemtype="http://schema.org/Person" id="' . $this->link() . '"';
        }
        $html .= '>';
        $children = array_map(function ($child) {
            return $child->name(true);
        }, $this->children());
        $c = count($children);
        if (! $c) {
            $c = 1;
        }
        $html .= '<tr>';
        $html .= $this->td($this->father()->father()->father(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->father()->father()->mother(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->father()->mother()->father(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->father()->mother()->mother(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->mother()->father()->father(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->mother()->father()->mother(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->mother()->mother()->father(), 'relatedTo', $c, 'ggparent');
        $html .= $this->td($this->mother()->mother()->mother(), 'relatedTo', $c, 'ggparent');
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= $this->td($this->father()->father(), 'relatedTo', $c * 2, 'gparent');
        $html .= $this->td($this->father()->mother(), 'relatedTo', $c * 2, 'gparent');
        $html .= $this->td($this->mother()->father(), 'relatedTo', $c * 2, 'gparent');
        $html .= $this->td($this->mother()->mother(), 'relatedTo', $c * 2, 'gparent');
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= $this->td($this->father(), 'parent', $c * 4);
        $html .= $this->td($this->mother(), 'parent', $c * 4);
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="self" colspan="' . $c * 8 . '"';
        $html .= '>';
        if ($schema) {
            $html .= '<span itemprop="name">';
        }
        $html .= $this->name();
        if ($schema) {
            $html .= '</span>';
            $html .= ' ' . $this->years(true);
            $html .= $this->_place('BIRT', 'birthPlace');
            $html .= $this->_place('DEAT', 'deathPlace');
            $html .= '<span itemprop="homeLocation" itemscope itemtype="http://schema.org/PostalAddress">';
            $html .= '<meta itemprop="description" content="' . $this->_place('DEAT', false, $this->_place('BIRT', false, 'Unknown')) . '" />';
            $html .= '</span>';
        }
        $html .= '</td>';
        $html .= '</tr>';
        if (! $this->isPrivate()) {
            $html .= '<tr>';
            $html .= implode(array_map(function ($child) use ($schema) {
                $childHtml = '<td colspan="8"';
                if ($schema && ! $child->isPrivate()) {
                    $childHtml .= ' itemprop="children" itemscope itemtype="http://schema.org/Person"';
                }
                $childHtml .= '>' . $child->name(true, $schema, $schema) . '</td>';

                return $childHtml;
            }, $this->children()));
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }

    /**
     * A lot of wild guessing here if we have no death record
     */
    public function isAlive()
    {
        if ($this->data('DEAT')) {
            return false;
        }
        if ($this->_year('BIRT') && ($this->_year('BIRT') < 1900)) {
            return false;
        }
        foreach ($this->children() as $child) {
            if ($child->_year('BIRT') && ($child->_year('BIRT') < 1930)) {
                return false;
            }
            foreach ($child->children() as $grandchild) {
                if ($grandchild->_year('BIRT') && ($grandchild->_year('BIRT') < 1960)) {
                    return false;
                }
                if ($grandchild->children()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function isPrivate()
    {
        if (! $this->id()) {
            return false;
        }
        if ($this->id() == 1) {
            return false;
        } // show dad
        if ($this->id() == 7) {
            return false;
        } // show me
        if ($this->id() == 157) {
            return false;
        } // show clare

        return $this->isAlive();
    }

    /**
     * Get one "level" of parents, grandparent etc
     * $this->parentIDs(); // parents
     * $this->parentIDs(2); // grandparents
     * $this->parentIDs(3); // great-grandparents
     */
    public function parentIDs($levelRequired = 1, $thisLevel = 1)
    {
        $ancestors = [];
        foreach (['father', 'mother'] as $parent) {
            if ($this->$parent()->id()) {
                if ($levelRequired == $thisLevel) {
                    array_push($ancestors, $this->$parent()->id());
                } else {
                    $ancestors = array_merge($ancestors, $this->$parent()->parentIDs($levelRequired, $thisLevel + 1));
                }
            }
        }

        return $ancestors;
    }

    public function ancestorIDs()
    {
        $ancestors = [];
        if ($this->father()->id()) {
            array_push($ancestors, $this->father()->id());
            $ancestors = array_merge($ancestors, $this->father()->ancestorIDs());
        }
        if ($this->mother()->id()) {
            array_push($ancestors, $this->mother()->id());
            $ancestors = array_merge($ancestors, $this->mother()->ancestorIDs());
        }

        return $ancestors;
    }

    public function hasAncestor($person, $level = 1, $debug = false)
    {
        // if ($debug) error_log(__METHOD__ . ' ' . $person->name() . ' / ' . $this->name());
        foreach (['father', 'mother'] as $parent) {
            if ($debug) {
                error_log(__METHOD__ . ' ' . $this->name() . '\'s ' . $parent . ' is ' . $this->$parent()->name());
            }
            if ($this->$parent()->id()) {
                // if ($debug) error_log(__METHOD__ . ' ' . $person->id() . ' == ' . $this->$parent()->id() . '? (for a ' . $level . ')');
                if ($person->id() == $this->$parent()->id()) {
                    return $level;
                }
                // if ($debug) error_log(__METHOD__ . ', no so test ancestors of ' . $parent);
                if ($newLevel = $this->$parent()->hasAncestor($person, $level + 1, $debug)) {
                    // if ($debug) error_log(__METHOD__ . ' got a ' . $newLevel);
                    return $newLevel;
                }
            }
        }

        return false;
    }

    /**
     * Return the relationship between this person and a list of potential others, as a sentence
     */
    public function relationship($people = [])
    {
        foreach ($people as $person) {
            if ($person->isPrivate()) {
                return;
            }
            if ($relationship = $this->_relationship($person)) {
                return $this->name() . ' is ' . $relationship . ' to ' . $person->name(true);
            }
        }
    }

    /**
     * @todo
     */
    public static function i18n($string)
    {
        return $string;
    }

    public static function commodore($number)
    {
        if ($number == 1) {
            return self::i18n('once');
        }
        if ($number == 2) {
            return self::i18n('twice');
        }

        return $number . ' ' . self::i18n('times');
    }

    public static function ordinal($number)
    {
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        }
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return $number . $ends[$number % 10];
    }

    /**
     * Return the relationship between this person and another, brief form
     */
    public function _relationship($person)
    {
        if ($this->id() === $person->id()) {
            return;
        }

        foreach ($person->spouses() as $spouse) {
            if ($this->id() == $spouse->id()) {
                return self::i18n('married');
            }
        }

        foreach (['father', 'mother'] as $parent) {
            if ($this->id() == $person->$parent()->id()) {
                return self::i18n($parent);
            }
        }

        if ($this->id() == $person->father()->father()->id()) {
            return self::i18n('paternal grandfather');
        }
        if ($this->id() == $person->father()->mother()->id()) {
            return self::i18n('paternal grandmother');
        }
        if ($this->id() == $person->mother()->father()->id()) {
            return self::i18n('maternal grandfather');
        }
        if ($this->id() == $person->mother()->mother()->id()) {
            return self::i18n('maternal grandmother');
        }

        if ($this->father()->id() == $person->id()) {
            return $this->childType();
        }
        if ($this->mother()->id() == $person->id()) {
            return $this->childType();
        }

        if ($this->father()->father()->id() == $person->id()) {
            return $this->grandChildType();
        }
        if ($this->father()->mother()->id() == $person->id()) {
            return $this->grandChildType();
        }
        if ($this->mother()->father()->id() == $person->id()) {
            return $this->grandChildType();
        }
        if ($this->mother()->mother()->id() == $person->id()) {
            return $this->grandChildType();
        }

        if (in_array($person->id(), $this->ancestorIDs())) {
            if ($level = $this->hasAncestor($person, 1)) {
                if ($level == 3) {
                    return $this->greatGrandChildType();
                }

                return ($level - 2) . 'x ' . $this->greatGrandChildType();
            }

            return self::i18n('descendent');
        }
        if (in_array($this->id(), $person->ancestorIDs())) {
            if ($level = $person->hasAncestor($this, 1)) {
                if ($level == 3) {
                    return $this->greatGrandParentType();
                }

                return ($level - 2) . 'x ' . $this->greatGrandParentType();
            }

            return self::i18n('ancestor');
        }
        if (array_intersect($this->parentIDs(), $person->parentIDs())) {
            return $this->siblingType();
        }
        for ($i = 2; $i < self::$generationsToName; $i++) {
            if (array_intersect($this->parentIDs($i), $person->parentIDs($i))) {
                return self::ordinal($i - 1) . ' ' . self::i18n('cousin');
            }
        }

        if (array_intersect($this->parentIDs(2), $person->parentIDs())) {
            return $this->siblingChildType();
        }
        /* for ($i = 3; $i < self::$generationsToName; $i ++) {
          if (array_intersect($this->parentIDs($i), $person->parentIDs())) return ($i - 3) . 'x ' self::i18n('great') . ' ' . $this->siblingChildType();
        } */
        if (array_intersect($this->parentIDs(), $person->parentIDs(2))) {
            return $this->parentSiblingType();
        }
        /* for ($i = 3; $i < self::$generationsToName; $i ++) {
          if (array_intersect($this->parentIDs($i), $person->parentIDs())) return ($i - 3) . 'x ' . self::i18n('great') . ' ' . $this->parentSiblingType();
        } */

        for ($i = 2; $i < self::$generationsToName; $i++) {
            for ($j = 2; $j < self::$generationsToName; $j++) {
                if ($i == $j) {
                    continue;
                } // already tested this
                if (array_intersect($this->parentIDs($i), $person->parentIDs($j))) {
                    return self::ordinal($i - 1) . ' ' . self::i18n('cousin') . ' ' . self::commodore(abs($i - $j)) . ' ' . self::i18n('removed');
                }
            }
        }
        if (array_intersect($this->ancestorIDs(), $person->ancestorIDs())) {
            return self::i18n('related');
        }

        return null;
    }

    public function __toString()
    {
        $parts = [];
        if (! $this->isPrivate()) {
            $occupation = $this->occupation();
            if ($occupation) {
                array_push($parts, $occupation);
            }
            foreach (['BIRT', 'BAPM', 'DEAT', 'BURI'] as $tag) {
                $content = $this->timeAndPlace($tag, true);
                if ($content) {
                    array_push($parts, $content);
                }
            }
            $notes = $this->notes();
            if ($notes) {
                array_push($parts, $notes);
            }
            $will = $this->will();
            if ($will) {
                array_push($parts, $will);
            }
        }
        array_push($parts, '</p>' . $this->tableTree(! $this->isPrivate()) . '<p>'); // @todo dreadful html
        if (isset($this->_data['SOUR'])) {
            array_push($parts, 'Source: ' . $this->source($this->_data['SOUR']));
        }
        if ($this->isPrivate()) {
            array_push($parts, 'Respecting the privacy of ' . $this->name() . ' (at least partly!). If you are ' . $this->name() . ' and you would like more of your details removed from this site please get in touch. Likewise if you can offer more details of your family tree, please also drop me a line!'); // @todo i18n
        }
        array_push($parts, ucfirst(self::i18n('father')) . ' ' . $this->father()->name(false, ! $this->father()->isPrivate()));
        array_push($parts, ucfirst(self::i18n('mother')) . ' ' . $this->mother()->name(false, ! $this->mother()->isPrivate()));
        if (! $this->isPrivate()) {
            foreach ($this->spouses() as $spouse) {
                array_push($parts, ucfirst(self::i18n('spouse')) . ' ' . $spouse->name(true));
            }
        }
        if (! $this->isPrivate()) {
            $siblings = array_map(function ($sibling) {
                return $sibling->name(true);
            }, $this->siblings());
            if (count($siblings)) {
                array_push($parts, ucfirst(self::i18n('siblings')) . ': ' . implode(', ', $siblings));
            }
        }

        return implode("\n\n", $parts);
    }
}
