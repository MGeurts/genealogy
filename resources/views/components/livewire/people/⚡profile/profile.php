<?php

declare(strict_types=1);

use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    /** @var array<string, string> */
    protected $listeners = [
        'person_updated' => 'render',
        'couple_deleted' => 'render',
    ];

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
