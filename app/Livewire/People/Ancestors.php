<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Contracts\AncestorsQueryInterface;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

final class Ancestors extends Component
{
    public Person $person;

    /**
     * @var Collection<int, object{
     *     id: int,
     *     firstname: string|null,
     *     surname: string|null,
     *     sex: string|null,
     *     father_id: int|null,
     *     mother_id: int|null,
     *     dod: string|null,
     *     yod: int|null,
     *     team_id: int|null,
     *     photo: string|null,
     *     dob: string|null,
     *     yob: int|null,
     *     degree: int,
     *     sequence: string
     * }>
     */
    public Collection $ancestors;

    public int $count_min = 1;

    public int $count = 3;          // default, showing 3 levels (person & parents & grandparents)

    public int $count_max = 128;    // maximum level depth

    /**
     * Set up the component data.
     */
    public function mount(AncestorsQueryInterface $ancestorsQuery): void
    {
        $this->loadAncestors($ancestorsQuery);
    }

    /**
     * Increment the count of ancestors displayed.
     */
    public function increment(): void
    {
        if ($this->count < $this->count_max) {
            $this->count++;
        }
    }

    /**
     * Decrement the count of ancestors displayed.
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
        return view('livewire.people.ancestors');
    }

    /**
     * Get the number of ancestors actually displayed (respecting $count).
     */
    public function getDisplayedAncestorsCountProperty(): int
    {
        return $this->ancestors
            ->where('degree', '<', $this->count) // keep within displayed levels
            ->count();
    }

    /**
     * Load ancestors from the database.
     */
    private function loadAncestors(AncestorsQueryInterface $ancestorsQuery): void
    {
        /** @phpstan-ignore assign.propertyType (Collection template covariance) */
        $this->ancestors = $ancestorsQuery->getAncestors($this->person->id, $this->count_max);

        $maxDegree       = $this->ancestors->max('degree');
        $this->count_max = min($maxDegree + 1, $this->count_max);

        if ($this->count > $this->count_max) {
            $this->count = $this->count_max;
        }
    }
}
