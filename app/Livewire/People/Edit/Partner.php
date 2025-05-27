<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\PartnerForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Partner extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public $couple;

    public PartnerForm $form;

    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();

        $this->persons = Person::PartnerOffset($this->person->birth_year)
            ->where('id', '!=', $this->person->id)
            ->orderBy('firstname')->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . (($p->sex === 'm') ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);
    }

    public function savePartner(): void
    {
        $validated = $this->form->validate();

        if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
            $this->toast()->error(__('app.create'), __('couple.overlap'))->send();
        } else {
            $this->couple->update([
                'person2_id' => $validated['person2_id'],
                'date_start' => $validated['date_start'] ?? null,
                'date_end'   => $validated['date_end'] ?? null,
                'is_married' => $validated['is_married'],
                'has_ended'  => $validated['date_end'] or $validated['has_ended'],
            ]);

            $this->toast()->success(__('app.save'), __('app.saved'))->flash()->send();

            $this->redirect('/people/' . $this->person->id);
        }
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.partner');
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->form->person2_id = ($this->couple->person1_id === $this->person->id) ? $this->couple->person2_id : $this->couple->person1_id;
        $this->form->date_start = $this->couple->date_start?->format('Y-m-d');
        $this->form->date_end   = $this->couple->date_end?->format('Y-m-d');
        $this->form->is_married = $this->couple->is_married;
        $this->form->has_ended  = $this->couple->has_ended;
    }

    private function hasOverlap($start, $end): bool
    {
        $is_overlap = false;

        if (! empty($start) or ! empty($end)) {
            foreach ($this->person->couples as $couple) {
                if (! empty($couple->date_start) and ! empty($couple->date_end)) {
                    if (! empty($start) and $start >= $couple->date_start and $start <= $couple->date_end) {
                        $is_overlap = true;
                    } elseif (! empty($end) and $end >= $couple->date_start and $end <= $couple->date_end) {
                        $is_overlap = true;
                    }
                }
            }
        }

        return $is_overlap;
    }
}
