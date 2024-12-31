<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
// use Laravel\Jetstream\Events\AddingTeam;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Import extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $user = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?TemporaryUploadedFile $file = null;

    public ?string $output = null;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'name'        => __('team.name'),
            'description' => __('team.description'),
            'file'        => __('gedcom.gedcom_file'),
        ];
    }

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = Auth()->user();
    }

    public function importTeam(): void
    {
        $this->toast()->success(__('app.saved'), strtoupper(__('app.under_construction')))->send();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.gedcom.import');
    }
}
