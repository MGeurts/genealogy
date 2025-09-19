<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use App\Models\Team;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Teams extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    // -----------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(Team::query()->with('owner')->withCount(['users', 'couples', 'persons']))
            ->columns([
                TextColumn::make('id')
                    ->label(__('team.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('team.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('team.description'))
                    ->searchable(),
                TextColumn::make('users_count')
                    ->label(__('team.users'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                TextColumn::make('persons_count')
                    ->label(__('team.persons'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                TextColumn::make('couples_count')
                    ->label(__('team.couples'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label(__('team.owner'))
                    ->sortable()
                    ->searchable(),
                IconColumn::make('personal_team')
                    ->label(__('team.team_personal') . '?')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('personal_team')
                    ->label(__('team.team_personal') . '?')
                    ->default(false),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->visible(fn (Team $record): bool => $record->isDeletable()),
            ])
            ->defaultSort('name')
            ->striped()
            ->extremePaginationLinks();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.developer.teams');
    }
}
