<?php

declare(strict_types=1);

namespace App\Livewire\Developer;

use App\Models\Person;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

final class People extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    // -----------------------------------------------------------------------
    public function table(Table $table): Table
    {
        return $table
            ->query(Person::query()->with(['children', 'couples']))
            ->columns([
                TextColumn::make('id')
                    ->label(__('person.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('photo')
                    ->label(__('person.avatar'))
                    ->getStateUsing(fn (Person $record) => $record->photo ? url('storage/photos-096/' . $record->team_id . '/' . $record->photo) : url('/img/avatar.png'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')
                    ->label(__('person.name'))
                    ->verticallyAlignStart()
                    ->url(fn (Person $record): string => '../people/' . $record->id)
                    ->color('info')
                    ->sortable(['surname', 'firstname'])
                    ->searchable(['surname', 'firstname']),
                TextColumn::make('birthname')
                    ->label(__('person.birthname'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nickname')
                    ->label(__('person.nickname'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sex')
                    ->label(__('person.sex'))
                    ->getStateUsing(fn (Person $record) => mb_strtoupper($record->sex))
                    ->searchable(),
                TextColumn::make('father.name')
                    ->label(__('person.father'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mother.name')
                    ->label(__('person.mother'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dob')
                    ->label(__('person.dob'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
                TextColumn::make('yob')
                    ->label(__('person.yob'))
                    ->sortable(),
                TextColumn::make('pob')
                    ->label(__('person.pob'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dod')
                    ->label(__('person.dod'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
                TextColumn::make('yod')
                    ->label(__('person.yod'))
                    ->sortable(),
                TextColumn::make('pod')
                    ->label(__('person.pod'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('team.name')
                    ->label(__('user.team'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime('Y-m-d H:i')->timezone(session('timezone') ?? 'UTC')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime('Y-m-d H:i')->timezone(session('timezone') ?? 'UTC')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('app.deleted_at'))
                    ->dateTime('Y-m-d H:i')->timezone(session('timezone') ?? 'UTC')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('sex')
                    ->options([
                        'm' => __('app.male'),
                        'f' => __('app.female'),
                    ])
                    ->label(__('person.sex')),
            ])
            ->actions([
                DeleteAction::make()
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn (Person $record): bool => $record->isDeletable()),
                ForceDeleteAction::make()
                    ->requiresConfirmation()
                    ->iconButton(),
                RestoreAction::make()
                    ->requiresConfirmation()
                    ->iconButton(),
            ])
            ->groups([
                Group::make('team.name')
                    ->label(__('team.team'))
                    ->collapsible(),
                Group::make('sex')
                    ->label(__('person.sex'))
                    ->collapsible(),
            ])
            ->defaultGroup('team.name')
            ->defaultSort('name')
            ->striped()
            ->extremePaginationLinks();
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.developer.people');
    }
}
