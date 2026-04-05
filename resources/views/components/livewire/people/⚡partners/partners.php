<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    #[On('couple_added')]
    #[On('couple_updated')]
    #[On('couple_deleted')]
    public function refreshPartners(): void
    {
        // optionally refresh any data here
        // Livewire will re-render automatically
    }

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
        $couple = Couple::where(function ($q) use ($couple): void {
            $q->where('person1_id', $this->person->id)
            ->orWhere('person2_id', $this->person->id);
        })->findOrFail($couple['id']);

        $couple->delete();

        $this->toast()->success(__('app.delete'), e($couple['name']) . ' ' . __('app.deleted') . '.')->send();

        $this->dispatch('couple_deleted');
    }
};
