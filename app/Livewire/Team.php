<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class Team extends Component
{
    use WithPagination;

    public User $user;

    /** @var array<string, int> */
    public array $teamCounts = [];

    public string $activeTab = 'users';     // [users, persons, couples]

    public int $perPage = 10;               // [5, 10, 25, 50, 100]

    public string $search = '';

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = User::with('currentTeam:id,name')->find(auth()->user()->id);

        $this->loadTeamCounts();
    }

    // -----------------------------------------------------------------------
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // -----------------------------------------------------------------------
    public function updatingActiveTab(): void
    {
        $this->resetPage();
    }

    // -----------------------------------------------------------------------
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        $paginatedData = match ($this->activeTab) {
            'users'   => $this->getPaginatedUsers(),
            'persons' => $this->getPaginatedPersons(),
            'couples' => $this->getPaginatedCouples(),
            default   => $this->getPaginatedUsers()
        };

        return view('livewire.team', [
            'paginatedData' => $paginatedData,
        ]);
    }

    // -----------------------------------------------------------------------
    private function loadTeamCounts(): void
    {
        $teamId = $this->user->current_team_id;

        $this->teamCounts = [
            'users'   => DB::table('team_user')->where('team_id', $teamId)->count(),
            'persons' => DB::table('people')->where('team_id', $teamId)->whereNull('deleted_at')->count(),
            'couples' => DB::table('couples')->where('team_id', $teamId)->count(),
        ];
    }

    // -----------------------------------------------------------------------
    /**
     * @return LengthAwarePaginator<int, array{id: int, name: string}>
     */
    private function getPaginatedUsers(): LengthAwarePaginator
    {
        $teamId = $this->user->current_team_id;

        $query = DB::table('team_user')
            ->join('users', 'team_user.user_id', '=', 'users.id')
            ->where('team_user.team_id', $teamId)
            ->whereNull('users.deleted_at')
            ->select('users.id', 'users.firstname', 'users.surname');

        if ($this->search) {
            $query->where(function ($q): void {
                $q->where('users.firstname', 'like', "%{$this->search}%")
                    ->orWhere('users.surname', 'like', "%{$this->search}%");
            });
        }

        $total   = $query->count();
        $results = $query->orderBy('users.surname')
            ->orderBy('users.firstname')
            ->offset(($this->getPage() - 1) * $this->perPage)
            ->limit($this->perPage)
            ->get()
            ->map(fn ($user): array => [
                'id'   => (int) $user->id,
                'name' => mb_trim(($user->firstname ?? '') . ' ' . $user->surname),
            ]);

        return new LengthAwarePaginator(
            $results,
            $total,
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    // -----------------------------------------------------------------------
    /**
     * @return LengthAwarePaginator<int, array{id: int, name: string, sex: string|null}>
     */
    private function getPaginatedPersons(): LengthAwarePaginator
    {
        $teamId = $this->user->current_team_id;

        $query = DB::table('people')
            ->where('team_id', $teamId)
            ->whereNull('deleted_at')
            ->select('id', 'firstname', 'surname', 'sex');

        if ($this->search) {
            $query->where(function ($q): void {
                $q->where('firstname', 'like', "%{$this->search}%")
                    ->orWhere('surname', 'like', "%{$this->search}%");
            });
        }

        $total   = $query->count();
        $results = $query->orderBy('surname')
            ->orderBy('firstname')
            ->offset(($this->getPage() - 1) * $this->perPage)
            ->limit($this->perPage)
            ->get()
            ->map(fn ($person): array => [
                'id'   => (int) $person->id,
                'name' => mb_trim(($person->firstname ?? '') . ' ' . $person->surname),
                'sex'  => $person->sex !== null ? (string) $person->sex : null,
            ]);

        // @phpstan-ignore return.type
        return new LengthAwarePaginator(
            $results,
            $total,
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    // -----------------------------------------------------------------------
    /**
     * @return LengthAwarePaginator<int, array{id: int, person1: array{id: int, name: string, sex: string|null}, person2: array{id: int, name: string, sex: string|null}}>
     */
    private function getPaginatedCouples(): LengthAwarePaginator
    {
        $teamId = $this->user->current_team_id;

        $query = DB::table('couples')
            ->join('people as p1', 'couples.person1_id', '=', 'p1.id')
            ->join('people as p2', 'couples.person2_id', '=', 'p2.id')
            ->where('couples.team_id', $teamId)
            ->whereNull('p1.deleted_at')
            ->whereNull('p2.deleted_at')
            ->select(
                'couples.id',
                'p1.id as person1_id',
                'p1.firstname as person1_firstname',
                'p1.surname as person1_surname',
                'p1.sex as person1_sex',
                'p2.id as person2_id',
                'p2.firstname as person2_firstname',
                'p2.surname as person2_surname',
                'p2.sex as person2_sex'
            );

        if ($this->search) {
            $query->where(function ($q): void {
                $q->where('p1.firstname', 'like', "%{$this->search}%")
                    ->orWhere('p1.surname', 'like', "%{$this->search}%")
                    ->orWhere('p2.firstname', 'like', "%{$this->search}%")
                    ->orWhere('p2.surname', 'like', "%{$this->search}%");
            });
        }

        $total   = $query->count();
        $results = $query->orderBy('p1.surname')
            ->orderBy('p1.firstname')
            ->offset(($this->getPage() - 1) * $this->perPage)
            ->limit($this->perPage)
            ->get()
            ->map(fn ($couple): array => [
                'id'      => (int) $couple->id,
                'person1' => [
                    'id'   => (int) $couple->person1_id,
                    'name' => mb_trim(($couple->person1_firstname ?? '') . ' ' . $couple->person1_surname),
                    'sex'  => $couple->person1_sex !== null ? (string) $couple->person1_sex : null,
                ],
                'person2' => [
                    'id'   => (int) $couple->person2_id,
                    'name' => mb_trim(($couple->person2_firstname ?? '') . ' ' . $couple->person2_surname),
                    'sex'  => $couple->person2_sex !== null ? (string) $couple->person2_sex : null,
                ],
            ]);

        // @phpstan-ignore return.type
        return new LengthAwarePaginator(
            $results,
            $total,
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }
}
