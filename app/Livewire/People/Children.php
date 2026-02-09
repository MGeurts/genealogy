<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Children extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    /** @var Collection<int, Person> */
    public Collection $children;

    // ------------------------------------------------------------------------------
    public function mount(): void
    {
        $this->children = $this->person->childrenNaturalAll();
    }

    public function confirm(int $child_id): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'disconnect',
                    'params' => $child_id,
                ],
            ])
            ->send();
    }

    public function disconnect(int $child_id): void
    {
        $child = Person::findOrFail($child_id);

        $key = $this->person->sex === 'm' ? 'father_id' : 'mother_id';
        $child->update([$key => null]);

        $this->toast()->success(__('app.disconnect'), e($child->name) . ' ' . __('app.disconnected') . '.')->send();

        $this->redirect(route('people.show', $this->person->id));
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.children');
    }
}
