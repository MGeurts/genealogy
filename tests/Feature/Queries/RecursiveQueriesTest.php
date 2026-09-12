<?php

declare(strict_types=1);

use App\Queries\SQLiteAncestorsQuery;
use App\Queries\SQLiteDescendantsQuery;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('SQLite recursive queries traverse a tree and stop at a cycle', function (): void {
    $originalDefaultConnection = config('database.default');
    $originalSqliteDatabase    = config('database.connections.sqlite.database');

    config()->set('database.default', 'sqlite');
    config()->set('database.connections.sqlite.database', ':memory:');
    DB::purge('sqlite');

    try {
        Schema::create('people', function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->primary();
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->string('sex')->nullable();
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->date('dod')->nullable();
            $table->unsignedInteger('yod')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('photo')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedInteger('yob')->nullable();
            $table->softDeletes();
        });

        DB::table('people')->insert([
            [
                'id'        => 1,
                'firstname' => 'Root',
                'sex'       => 'm',
                'father_id' => 3,
            ],
            [
                'id'        => 2,
                'firstname' => 'Child',
                'sex'       => 'm',
                'father_id' => 1,
            ],
            [
                'id'        => 3,
                'firstname' => 'Grandchild',
                'sex'       => 'm',
                'father_id' => 2,
            ],
        ]);

        $ancestors   = (new SQLiteAncestorsQuery())->getAncestors(3, 10);
        $descendants = (new SQLiteDescendantsQuery())->getDescendants(1, 10);

        expect($ancestors->pluck('id')->all())->toBe([3, 2, 1])
            ->and($ancestors->max('degree'))->toBe(2)
            ->and($descendants->pluck('id')->all())->toBe([1, 2, 3])
            ->and($descendants->max('degree'))->toBe(2);
    } finally {
        DB::purge('sqlite');
        config()->set('database.connections.sqlite.database', $originalSqliteDatabase);
        config()->set('database.default', $originalDefaultConnection);
    }
});
