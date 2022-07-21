<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domains = [
            [
                'id'    => 1,
                'order' => -1,
                'name'  => 'Transition',
                'slug'  => 'transition',
            ],
            [
                'id'    => 2,
                'order' => 0,
                'name'  => 'Economy',
                'slug'  => 'economy',
            ],
            [
                'id'    => 3,
                'order' => 1,
                'name'  => 'Environment',
                'slug'  => 'environment',
            ],
            [
                'id'    => 4,
                'order' => 2,
                'name'  => 'Governance',
                'slug'  => 'governance',
            ],
            [
                'id'    => 5,
                'order' => 3,
                'name'  => 'Science',
                'slug'  => 'science',
            ],
            [
                'id'    => 6,
                'order' => 4,
                'name'  => 'Society',
                'slug'  => 'society',
            ],
        ];

        Domain::insert($domains);
    }
}
