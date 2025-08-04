<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Facades\MediaLibrary;
use App\Models\Person;
use App\Queries\DescendantQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

final class Descendants extends Component
{
    public Person $person;

    public Collection $descendants;

    public int $count_min = 1;

    public int $count = 3;          // default, showing 3 levels (person & parents & grandparents)

    public int $count_max = 128;    // maximum level depth, choose carefully from listing below

    // --------------------------------------------------------------------------------------------------------------------
    // REMARK : The maximum length of the comma separated sequence of all id's in the tree can NOT succeed 1024 characters!
    //          So, when largest id is 3 digits (max        999), the maximum level depth is 1024 / (3 + 1) = 256 levels
    //              when largest id is 4 digits (max      9.999), the maximum level depth is 1024 / (4 + 1) = 204 levels
    //              when largest id is 5 digits (max     99.999), the maximum level depth is 1024 / (5 + 1) = 170 levels
    //              when largest id is 6 digits (max    999.999), the maximum level depth is 1024 / (6 + 1) = 146 levels
    //              when largest id is 7 digits (max  9.999.999), the maximum level depth is 1024 / (7 + 1) = 128 levels
    //              when largest id is 8 digits (max 99.999.999), the maximum level depth is 1024 / (8 + 1) = 113 levels
    //              ...
    // --------------------------------------------------------------------------------------------------------------------

    /**
     * Set up the component data.
     */
    public function mount(): void
    {
        $this->loadDescendants();
    }

    /**
     * Increment the count of descendants displayed.
     */
    public function increment(): void
    {
        if ($this->count < $this->count_max) {
            $this->count++;
        }
    }

    /**
     * Decrement the count of descendants displayed.
     */
    public function decrement(): void
    {
        if ($this->count > $this->count_min) {
            $this->count--;
        }
    }

    /**
     * Render the Livewire component view.
     */
    public function render(): View
    {
        return view('livewire.people.descendants');
    }

    /**
     * Load descendants from the database with recursion.
     */
    private function loadDescendants(): void
    {
        $this->descendants = collect(DB::select(DescendantQuery::get($this->person->id, $this->count_max)));

        $this->descendants = MediaLibrary::loadTreePeopleImageUrl($this->descendants);

        $maxDegree       = $this->descendants->max('degree');
        $this->count_max = min($maxDegree + 1, $this->count_max);

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }
}
