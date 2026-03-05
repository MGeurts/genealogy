<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /**
     * @var array<string, string>
     */
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

    /**
     * @param  array{id: int, name: string}  $couple
     */
    public function delete(array $couple): void
    {
        Couple::findOrFail($couple['id'])->delete();

        $this->toast()->success(__('app.delete'), e($couple['name']) . ' ' . __('app.deleted') . '.')->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    
};
