<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

final class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            'Agender',
            'Androgyne',
            'Androgynous',
            'Bigender',
            'Cis',
            'Cisgender',
            'Cis Female',
            'Cis Male',
            'Cis Man',
            'Cis Woman',
            'Cisgender Female',
            'Cisgender Male',
            'Cisgender Man',
            'Cisgender Woman',
            'Female to Male',
            'FTM',
            'Gender Fluid',
            'Gender Nonconforming',
            'Gender Questioning',
            'Gender Variant',
            'Genderqueer',
            'Intersex',
            'Male to Female',
            'MTF',
            'Neither',
            'Neutrois',
            'Non-binary',
            'Other',
            'Pangender',
            'Trans',
            'Trans*',
            'Trans Female',
            'Trans* Female',
            'Trans Male',
            'Trans* Male',
            'Trans Man',
            'Trans* Man',
            'Trans Person',
            'Trans* Person',
            'Trans Woman',
            'Trans* Woman',
            'Transfeminine',
            'Transgender',
            'Transgender Female',
            'Transgender Male',
            'Transgender Man',
            'Transgender Person',
            'Transgender Woman',
            'Transmasculine',
            'Transsexual',
            'Transsexual Female',
            'Transsexual Male',
            'Transsexual Man',
            'Transsexual Person',
            'Transsexual Woman',
            'Two-Spirit',
        ];

        $now     = now();
        $payload = collect($genders)
            ->map(fn (string $name) => [
                'name'       => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

        Gender::insert($payload);
    }
}
