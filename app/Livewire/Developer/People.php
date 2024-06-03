<?php

namespace App\Livewire\Developer;

use App\Models\Person;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class People extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Person::query()->with(['children', 'couples']))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('person.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('photo')
                    ->label(__('person.avatar'))
                    ->getStateUsing(function (Person $record) {
                        return $record->photo ? url('storage/photos-096/' . $record->team_id . '/' . $record->photo) : url('/img/avatar.png');
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('person.name'))
                    ->verticallyAlignStart()
                    ->url(fn (Person $record) => 'people/' . $record->id)
                    ->color('info')
                    ->sortable(['surname', 'firstname'])
                    ->searchable(['surname', 'firstname']),
                Tables\Columns\TextColumn::make('birthname')
                    ->label(__('person.birthname'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nickname')
                    ->label(__('person.nickname'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex')
                    ->label(__('person.sex'))
                    ->getStateUsing(function (Person $record) {
                        return strtoupper($record->sex);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('father.name')
                    ->label(__('person.father'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother.name')
                    ->label(__('person.mother'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('parents.name')
                //     ->label(__('person.parents'))
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('dob')
                    ->label(__('person.dob'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('yob')
                    ->label(__('person.yob'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('pob')
                    ->label(__('person.pob'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('dod')
                    ->label(__('person.dod'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('yod')
                    ->label(__('person.yod'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('pod')
                    ->label(__('person.pod'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('team.name')
                    ->label(__('user.team'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('app.deleted_at'))
                    ->dateTime('Y-m-d H:i')->timezone(auth()->user()->timezone)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('sex')
                    ->options([
                        'm' => __('app.male'),
                        'f' => __('app.female'),
                    ])
                    ->label(__('person.sex')),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->visible(function (Person $record) {
                        return $record->isDeletable();
                    }),
                Tables\Actions\ForceDeleteAction::make()->iconButton(),
                Tables\Actions\RestoreAction::make()->iconButton(),
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
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public function render(): View
    {
        return view('livewire.developer.persons');
    }
}
