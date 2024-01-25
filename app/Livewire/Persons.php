<?php

namespace App\Livewire;

use App\Models\person;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class Persons extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(person::query())
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
                Tables\Columns\TextColumn::make('gender.name')
                    ->label(__('person.gender'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('father.name')
                    ->label(__('person.father'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('mother.name')
                    ->label(__('person.mother'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('parents.name')
                    ->label(__('person.parents'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('dob')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('yob')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pob')
                    ->searchable(),
                Tables\Columns\IconColumn::make('birth_order')
                    ->boolean(),

                Tables\Columns\TextColumn::make('dod')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('yod')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pod')
                    ->searchable(),

                Tables\Columns\TextColumn::make('street')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),

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
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.persons');
    }
}
