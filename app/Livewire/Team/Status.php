<?php

declare(strict_types=1);

namespace App\Livewire\Team;

use Illuminate\View\View;
use Livewire\Component;

// use AllowDynamicProperties;

// #[AllowDynamicProperties]
class Status extends Component
{
    public $team;

    public array $headers = [];

    public array $rows = [];

    protected $listeners = [
        'team_updated' => 'mount',
    ];

    public function mount(): void
    {
        $this->headers = [
            ['index' => 'object', 'label' => $this->team->name],
            ['index' => 'count', 'label' => '#'],
        ];

        $this->rows = [
            ['object' => __('team.users'), 'count' => count($this->team->users)],
            ['object' => __('team.persons'), 'count' => count($this->team->persons)],
            ['object' => __('team.couples'), 'count' => count($this->team->couples)],
        ];
    }

    public function render(): View
    {
        return view('livewire.team.status');
    }
}
