<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\Rules\DodValid;
use App\Rules\YodValid;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Death extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    #[Validate]
    public ?int $yod = null;

    #[Validate]
    public ?string $dod = null;

    public ?string $pod = null;

    public ?string $cemetery_location_name = null;

    public ?string $cemetery_location_address = null;

    public ?string $cemetery_location_latitude = null;

    public ?string $cemetery_location_longitude = null;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveDeath(): void
    {
        $validated = $this->validate();

        $this->person->update([
            'yod' => $this->yod ?? null,
            'dod' => $this->dod ?? null,
            'pod' => $this->pod ?? null,
        ]);

        // ------------------------------------------------------
        // update or create metadata
        // ------------------------------------------------------
        /** @var array<string, mixed> $validated */
        $this->person->updateMetadata(
            collect($validated)
                ->forget(['yod', 'dod', 'pod'])
                ->filter(fn ($value, $key): bool => $value !== $this->person->getMetadataValue($key))
        );
        // ------------------------------------------------------

        $this->dispatch('person_updated');

        $this->toast()->success(__('app.save'), __('app.saved'))->send();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.death');
    }

    // ------------------------------------------------------------------------------
    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'yod'                         => ['nullable', 'integer', 'min:1', 'max:' . date('Y'), new YodValid],
            'dod'                         => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', new DodValid],
            'pod'                         => ['nullable', 'string', 'max:255'],
            'cemetery_location_name'      => ['nullable', 'string', 'max:255'],
            'cemetery_location_address'   => ['nullable', 'string', 'max:255'],
            'cemetery_location_latitude'  => ['nullable', 'numeric', 'decimal:0,13', 'min:-90', 'max:90', 'required_with:cemetery_location_longitude'],
            'cemetery_location_longitude' => ['nullable', 'numeric', 'decimal:0,13', 'min:-180', 'max:180', 'required_with:cemetery_location_latitude'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'yod'                         => __('person.yod'),
            'dod'                         => __('person.dod'),
            'pod'                         => __('person.pod'),
            'cemetery_location_name'      => __('metadata.location_name'),
            'cemetery_location_address'   => __('metadata.address'),
            'cemetery_location_latitude'  => __('metadata.latitude'),
            'cemetery_location_longitude' => __('metadata.longitude'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->yod                         = $this->person->yod;
        $this->dod                         = $this->person->dod ? Carbon::parse($this->person->dod)->format('Y-m-d') : null;
        $this->pod                         = $this->person->pod;
        $this->cemetery_location_name      = $this->person->getMetadataValue('cemetery_location_name');
        $this->cemetery_location_address   = $this->person->getMetadataValue('cemetery_location_address');
        $this->cemetery_location_latitude  = $this->person->getMetadataValue('cemetery_location_latitude');
        $this->cemetery_location_longitude = $this->person->getMetadataValue('cemetery_location_longitude');
    }
}
