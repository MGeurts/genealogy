<?php

declare(strict_types=1);

namespace App\Livewire\People\Add;

use App\Livewire\Forms\People\PersonForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Child extends Component
{
    use Interactions, WithFileUploads;
    use TrimStringsAndConvertEmptyStringsToNull;

    public Person $person;

    public PersonForm $form;

    public Collection $persons;

    public ?string $selectedTab = null;

    public function mount(): void
    {
        $this->persons = Person::where('id', '!=', $this->person->id)
            ->whereNull($this->person->sex === 'm' ? 'father_id' : 'mother_id')
            ->YoungerThan($this->person->birth_year)
            ->orderBy('firstname')
            ->orderBy('surname')
            ->get()
            ->map(fn ($p): array => [
                'id'   => $p->id,
                'name' => $p->name . ' [' . ($p->sex === 'm' ? __('app.male') : __('app.female')) . '] ' . ($p->birth_formatted ? ' (' . $p->birth_formatted . ')' : ''),
            ]);

        $this->selectedTab = $this->persons->isEmpty() ? __('person.add_new_person_as_child') : __('person.add_existing_person_as_child');
    }

    public function updatingUploads(): void
    {
        $this->form->backup = $this->form->uploads;
    }

    public function updatedUploads(): void
    {
        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect(array_merge($this->form->backup, (array) $this->form->uploads))
            ->unique(fn (UploadedFile $file): string => $file->getClientOriginalName())
            ->toArray();
    }

    public function deleteUpload(array $content): void
    {
        /* the $content contains:
            [
                'temporary_name',
                'real_name',
                'extension',
                'size',
                'path',
                'url',
            ]
        */

        if (empty($this->form->uploads)) {
            return;
        }

        $this->form->uploads = collect($this->form->uploads)
            ->filter(fn (UploadedFile $file): bool => $file->getFilename() !== $content['temporary_name'])
            ->values()
            ->toArray();

        rescue(
            fn () => File::delete(storage_path('app/livewire-tmp/' . $content['temporary_name'])),
            report: false
        );
    }

    public function saveChild(): void
    {
        $validated = $this->validate();

        if (isset($validated['person_id'])) {
            $child = Person::findOrFail($validated['person_id']);

            $child->update([
                $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
            ]);

            $this->toast()->success(__('app.save'), __('person.existing_person_linked_as_child'))->flash()->send();
        } else {
            $newChild = Person::create(array_merge(
                collect($validated)->only(['firstname', 'surname', 'birthname', 'nickname', 'sex', 'gender_id', 'yob', 'dob', 'pob'])->toArray(),
                [
                    $this->person->sex === 'm' ? 'father_id' : 'mother_id' => $this->person->id,
                    'team_id'                                              => $this->person->team_id,
                ]
            ));

            if ($this->form->uploads) {
                $photos = new PersonPhotos($newChild);
                $photos->save($this->form->uploads);
            }

            $this->toast()->success(__('app.create'), __('person.new_person_linked_as_child'))->flash()->send();
        }

        $this->redirect(route('people.show', $this->person->id));
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.add.child');
    }
}
