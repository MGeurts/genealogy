<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

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

    // -----------------------------------------------------------------------
    public $person2_id = null;

    public $date_start = null;

    public $date_end = null;

    public $is_married = false;

    public $has_ended = false;

    public $couple;

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
        $validated = $this->validate();

        // Custom rule: If date_end is set, has_ended must be true
        if ($validated['date_end'] && ! $validated['has_ended']) {
            $this->addError('has_ended', __('couple.required_if_date_end'));

            return;
        }

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

    // -----------------------------------------------------------------------
    protected function rules(): array
    {
        return $rules = [
            'person2_id' => ['required', 'integer', 'exists:people,id'],
            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'has_ended.required_if' => __('couple.required_if_date_end'),
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'person2_id' => __('couple.partner'),
            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->person2_id = ($this->couple->person1_id === $this->person->id) ? $this->couple->person2_id : $this->couple->person1_id;
        $this->date_start = $this->couple->date_start?->format('Y-m-d');
        $this->date_end   = $this->couple->date_end?->format('Y-m-d');
        $this->is_married = $this->couple->is_married;
        $this->has_ended  = $this->couple->has_ended;
    }

    private function hasOverlap($start, $end): bool
    {
        if (empty($start) && empty($end)) {
            return false;
        }

        foreach ($this->person->couples as $couple) {
            // Skip the current couple being edited
            if ($this->couple && $couple->id === $this->couple->id) {
                continue;
            }

            if (! empty($couple->date_start) && ! empty($couple->date_end)) {
                if (! empty($start) && $start >= $couple->date_start && $start <= $couple->date_end) {
                    return true;
                }

                if (! empty($end) && $end >= $couple->date_start && $end <= $couple->date_end) {
                    return true;
                }
            }
        }

        return false;
    }
}
