<?php

declare(strict_types=1);

use App\Models\Person;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    // -----------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    #[On('person_updated')]
    #[On('couple_deleted')]
    #[On('person_added_as_child')]
    #[On('person_disconnected_as_child')]
    public function refreshProfile(): void
    {
        // optionally refresh any data here
        // Livewire will re-render automatically
    }

    // -----------------------------------------------------------------------
    public function confirm(): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'delete',
                ],
            ])
            ->send();
    }

    public function delete(): void
    {
        if ($this->person->isDeletable()) {
            $this->person->delete();

            $this->toast()->success(__('app.delete'), e($this->person->name) . ' ' . __('app.deleted') . '.')->send();

            $this->redirect('/search');
        }
    }
};
