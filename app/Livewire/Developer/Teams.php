<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use App\Models\Team;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

final class Teams extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    // -----------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(Team::query()->with('owner')->withCount(['users', 'couples', 'persons']))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('team.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('team.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('team.description'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label(__('team.users'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('persons_count')
                    ->label(__('team.persons'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('couples_count')
                    ->label(__('team.couples'))
                    ->badge()
                    ->color(static fn ($state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('team.owner'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('personal_team')
                    ->label(__('team.team_personal') . '?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime('Y-m-d H:i')->timezone(Auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime('Y-m-d H:i')->timezone(Auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('personal_team')
                    ->label(__('team.team_personal') . '?')
                    ->default(false),
            ])
            ->defaultSort('name')
            ->striped();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.developer.teams');
    }
}
