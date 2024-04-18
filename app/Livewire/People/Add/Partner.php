<?php

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PartnerForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Partner extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public $person;

    public PartnerForm $partnerForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->partnerForm->person2_id = null;

        $this->partnerForm->date_start = null;
        $this->partnerForm->date_end   = null;

        $this->partnerForm->is_married = false;
        $this->partnerForm->has_ended  = false;
    }

    public function savePartner()
    {
        if ($this->isDirty()) {
            $validated = $this->partnerForm->validate();

            if ($this->hasOverlap($validated['date_start'], $validated['date_end'])) {
                $this->toast()->error(__('app.create'), 'RELATIONSHIP OVERLAP !!')->send();
            } else {
                $couple = Couple::create([
                    'person1_id' => $this->person->id,
                    'person2_id' => $validated['person2_id'],
                    'date_start' => $validated['date_start'] ?? null,
                    'date_end'   => $validated['date_end'] ?? null,
                    'is_married' => $validated['is_married'],
                    'has_ended'  => $validated['has_ended'],
                    'team_id'    => auth()->user()->current_team_id,
                ]);

                $this->toast()->success(__('app.create'), __('app.created'))->flash()->send();

                return $this->redirect('/people/' . $this->person->id);
            }
        }
    }

    public function resetPartner(): void
    {
        $this->mount();
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

    public function isDirty(): bool
    {
        return
        $this->partnerForm->person2_id != null or
        $this->partnerForm->date_start != null or
        $this->partnerForm->date_end != null or
        $this->partnerForm->is_married != false or
        $this->partnerForm->has_ended != false;
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $persons = Person::PartnerOffset($this->person->birth_date, $this->person->birth_year)
            ->orderBy('firstname', 'asc')->orderBy('surname', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'name' => $p->name . ' [' . strtoupper($p->sex) . '] (' . $p->birth_formatted . ')',
                ];
            })->toArray();

        return view('livewire.people.add.partner')->with(compact('persons'));
    }
}
