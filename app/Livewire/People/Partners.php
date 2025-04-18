<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Couple;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Partners extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'couple_deleted' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function confirm(int $id, string $name): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'delete',
                    'params' => [
                        'id'   => $id,
                        'name' => $name,
                    ],
                ],
            ])
            ->send();
    }

    public function delete(array $couple): void
    {
        Couple::findOrFail($couple['id'])->delete();

        $this->toast()->success(__('app.delete'), $couple['name'] . ' ' . __('app.deleted') . '.')->flash()->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.partners');
    }
}
