<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Couple;
use App\Models\Person;
use DateTimeInterface;
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
    public ?int $partner_id = null;

    public ?string $date_start = null;

    public ?string $date_end = null;

    public ?bool $is_married = false;

    public ?bool $has_ended = false;

    public Couple $couple;

    /** @var Collection<int, array{id: int, name: string}> */
    public Collection $persons;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();

        $this->persons = Person::partnerOffset($this->person->dob, $this->person->yob)
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

        // Relationship overlap check
        if ($this->overlapsWith($validated['date_start'], $validated['date_end'])) {
            return;
        }

        if ($this->person->id === $this->couple->person1_id) {
            $this->couple->update([
                'person2_id' => $validated['partner_id'],
                'date_start' => $validated['date_start'] ?? null,
                'date_end'   => $validated['date_end'] ?? null,
                'is_married' => $validated['is_married'],
                'has_ended'  => $validated['date_end'] or $validated['has_ended'],
            ]);
        } elseif ($this->person->id === $this->couple->person2_id) {
            $this->couple->update([
                'person1_id' => $validated['partner_id'],
                'date_start' => $validated['date_start'] ?? null,
                'date_end'   => $validated['date_end'] ?? null,
                'is_married' => $validated['is_married'],
                'has_ended'  => $validated['date_end'] or $validated['has_ended'],
            ]);
        }

        $this->toast()->success(__('app.save'), __('app.saved'))->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.partner');
    }

    // -----------------------------------------------------------------------
    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return $rules = [
            'partner_id' => ['required', 'integer', 'exists:people,id'],
            'date_start' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'before:date_end'],
            'date_end'   => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today', 'after:date_start'],
            'is_married' => ['nullable', 'boolean'],
            'has_ended'  => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'has_ended.required_if' => __('couple.required_if_date_end'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'partner_id' => __('couple.partner'),
            'date_start' => __('couple.date_start'),
            'date_end'   => __('couple.date_end'),
            'is_married' => __('couple.is_married'),
            'has_ended'  => __('couple.has_ended'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->partner_id = ($this->couple->person1_id === $this->person->id) ? $this->couple->person2_id : $this->couple->person1_id;

        $dateStart        = $this->couple->date_start;
        $this->date_start = ($dateStart instanceof DateTimeInterface) ? $dateStart->format('Y-m-d') : null;

        $dateEnd        = $this->couple->date_end;
        $this->date_end = ($dateEnd instanceof DateTimeInterface) ? $dateEnd->format('Y-m-d') : null;

        $this->is_married = $this->couple->is_married;
        $this->has_ended  = $this->couple->has_ended;
    }

    private function overlapsWith(?string $start, ?string $end): bool
    {
        $newStart = $start ?? '0000-01-01';
        $newEnd   = $end ?? '9999-12-31';

        foreach ($this->person->couples as $couple) {
            if ($couple->id === $this->couple->id) {
                continue;
            }

            $existingStart = $couple->date_start instanceof DateTimeInterface ? $couple->date_start->format('Y-m-d') : '0000-01-01';
            $existingEnd   = $couple->date_end instanceof DateTimeInterface ? $couple->date_end->format('Y-m-d') : '9999-12-31';

            if ($newStart <= $existingEnd && $newEnd >= $existingStart) {
                $startOverlaps = $start !== null && $start >= $existingStart && $start <= $existingEnd;
                $endOverlaps   = $end !== null && $end >= $existingStart && $end <= $existingEnd;

                if ($startOverlaps && $endOverlaps) {
                    $this->addError('date_start', __('couple.overlap'));
                    $this->addError('date_end', __('couple.overlap'));
                } elseif ($startOverlaps) {
                    $this->addError('date_start', __('couple.overlap'));
                } elseif ($endOverlaps) {
                    $this->addError('date_end', __('couple.overlap'));
                } else {
                    // Engulfment: new range straddles the existing one entirely
                    $this->addError('date_start', __('couple.overlap'));
                    $this->addError('date_end', __('couple.overlap'));
                }

                return true;
            }
        }

        return false;
    }
}
