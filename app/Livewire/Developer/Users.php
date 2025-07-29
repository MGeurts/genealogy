<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

final class Users extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // -----------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            // ->query(user::query()->with(['teams', 'ownedTeams.users', 'ownedTeams.couples', 'ownedTeams.persons']))
            ->query(User::query()->with(['teams', 'ownedTeams']))
            ->columns([
                TextColumn::make('id')
                    ->label(__('user.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('profile_photo_path')
                    ->label(__('user.photo'))
                    ->getStateUsing(fn (User $record) => $record->profile_photo_path ? url('storage/' . $record->profile_photo_path) : url('/img/avatar.png'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->label(__('user.name'))
                    ->verticallyAlignStart()
                    ->sortable(['surname', 'firstname'])
                    ->searchable(['surname', 'firstname']),
                TextColumn::make('email')
                    ->label(__('user.email'))
                    ->verticallyAlignStart()
                    ->searchable(),
                IconColumn::make('email_verified')
                    ->label(__('user.email_verified') . '?')
                    ->verticallyAlignStart()
                    ->getStateUsing(fn (User $record) => $record->email_verified_at)
                    ->boolean(),
                TextColumn::make('two_factor_confirmed_at')
                    ->label(__('user.two_factor_confirmed_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone),
                TextColumn::make('seen_at')
                    ->label(__('user.seen_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable(),
                TextColumn::make('team_personal')
                    ->label(__('team.team_personal'))
                    ->verticallyAlignStart()
                    ->getStateUsing(fn (User $record) => $record->personalTeam()->name),
                TextColumn::make('teams')
                    ->label(__('team.teams'))
                    ->getStateUsing(fn (User $record): string => implode('<br/>', $record->allTeams()->where('personal_team', false)->pluck(['name'])->toArray()))
                    ->verticallyAlignStart()
                    ->html(),
                TextColumn::make('language')
                    ->label(__('user.language'))
                    ->verticallyAlignStart()
                    ->getStateUsing(fn (User $record) => mb_strtoupper($record->language))
                    ->sortable(),
                IconColumn::make('is_developer')
                    ->label(__('user.developer') . '?')
                    ->verticallyAlignStart()
                    ->boolean(),
                TextColumn::make('email_verified_at')
                    ->label(__('user.email_verified_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('app.deleted_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('language')
                    ->options(array_flip(config('app.available_locales'))),
                TernaryFilter::make('is_developer')
                    ->label(__('user.developer') . '?'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()
                    ->visible(fn (User $record): bool => $record->isDeletable()),
                ForceDeleteAction::make()
                    ->iconButton(),
                RestoreAction::make()
                    ->iconButton(),
            ])
            ->defaultSort('name')
            ->striped()
            ->extremePaginationLinks();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.developer.users');
    }
}
