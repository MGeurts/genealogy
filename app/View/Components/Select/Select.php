<?php

namespace App\View\Components\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public $id;

    public $name;

    public $options;

    public $valueField;

    public $textField;

    public $value;

    public $placeholder;

    public $searchInputPlaceholder;

    public $noOptions;

    public $noResult;

    public $disabled;

    public $required;

    public $multiple;

    public $maxSelection;

    public $searchable;

    public $clearable;

    /**
     * Create a new component instance.
     */
    public function __construct(
        array $options = [],
        ?string $id = null,
        ?string $name = null,
        $valueField = 'value',
        $textField = 'text',
        $value = null,
        $placeholder = 'Select Option',
        $searchInputPlaceholder = 'Search...',
        $noOptions = 'No option data.',
        $noResult = 'No results match your search.',
        $maxSelection = 0,
        $searchable = true,
        $clearable = false
    ) {
        $this->options = $options;
        $this->id = $id;
        $this->name = $name;
        $this->valueField = $valueField;
        $this->textField = $textField;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->searchInputPlaceholder = $searchInputPlaceholder;
        $this->noOptions = $noOptions;
        $this->noResult = $noResult;
        $this->maxSelection = $maxSelection;
        $this->searchable = $searchable;
        $this->clearable = $clearable;

        if (! $this->id) {
            $this->id = 'simple-select-' . uniqid();
        }

        if (! $this->name) {
            $this->name = $this->id;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select.select');
    }
}
