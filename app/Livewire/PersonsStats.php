<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Person;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonsStats extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Person::query())
            ->columns([
                Tables\Columns\TextColumn::make('surname')
                    ->label(__('person.surname'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label(__('person.firstname'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthname')
                    ->label(__('person.birthname'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('nickname')
                    ->label(__('person.nickname'))
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
                Tables\Columns\TextColumn::make('parents.name')
                    ->label(__('person.parents'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Columns\IconColumn::make('photo')
                    ->label(__('person.photo'))
                    ->getStateUsing(function (Person $record) {
                        return $record->photo ? true : false;
                    })
                    ->boolean(),
                Tables\Columns\TextColumn::make('team.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('person.created_at'))
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('person.updated_at'))
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('person.deleted_at'))
                    ->dateTime('Y-m-d h:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ])
            ->groups([
                Group::make('team.name')
                    ->collapsible(),
            ])
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderBy('surname')
                    ->orderBy('firstname');
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

    public function render(): View
    {
        return view('livewire.persons-stats');
    }
}
