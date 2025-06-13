<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

final class Team extends Component
{
    // ------------------------------------------------------------------------------
    public $user;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = User::with([
            'currentTeam.users',
            'currentTeam.persons',
            'currentTeam.couples',
            'currentTeam.couples.person_1',
            'currentTeam.couples.person_2',
        ])->find(auth()->user()->id);
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.team');
    }
}
