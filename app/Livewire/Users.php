<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Users extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(user::query()->with('ownedTeams'))
            ->columns([
                Tables\Columns\TextColumn::make('surname')
                    ->label(__('user.surname'))
                    ->verticallyAlignStart()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label(__('user.firstname'))
                    ->verticallyAlignStart()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('user.email'))
                    ->verticallyAlignStart()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('user.email_verified_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d h:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('two_factor_confirmed_at')
                    ->label(__('user.two_factor_confirmed_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d h:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('team_personal')
                    ->label(__('team.team_personal'))
                    ->verticallyAlignStart()
                    ->getStateUsing(function (User $record) {
                        return $record->personalTeam()->name;
                    }),
                Tables\Columns\TextColumn::make('teams')
                    ->label(__('team.teams'))
                    ->getStateUsing(function (User $record) {
                        return implode('<br/>', $record->teams()->pluck('name')->toArray());
                    })
                    ->verticallyAlignStart()
                    ->html(),
                Tables\Columns\TextColumn::make('current_team.name')
                    ->label(__('user.current_team'))
                    ->verticallyAlignStart(),
                Tables\Columns\TextColumn::make('language')
                    ->label(__('user.language'))
                    ->verticallyAlignStart()
                    ->getStateUsing(function (User $record) {
                        return strtoupper($record->language);
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_developer')
                    ->label(__('user.developer') . '?')
                    ->verticallyAlignStart()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('user.created_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('user.updated_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('user.deleted_at'))
                    ->verticallyAlignStart()
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('language')
                    ->options(array_flip(config('app.available_locales'))),
                TernaryFilter::make('is_developer')
                    ->label(__('user.developer') . '?'),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\ForceDeleteAction::make()->iconButton(),
                Tables\Actions\RestoreAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort(function (Builder $query): Builder {
                return $query->orderBy('surname')->orderBy('firstname');
            })
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public function render()
    {
        return view('livewire.users');
    }
}
