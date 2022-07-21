<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            [
                'id'    => 1,
                'order' => 0,
                'name'  => 'Arts',
                'slug'  => 'arts',
            ],
            [
                'id'    => 2,
                'order' => 1,
                'name'  => 'Science',
                'slug'  => 'science',
            ],
            [
                'id'    => 3,
                'order' => 2,
                'name'  => 'Climate',
                'slug'  => 'climate',
            ],
            [
                'id'    => 4,
                'order' => 3,
                'name'  => 'History',
                'slug'  => 'history',
            ],
            [
                'id'    => 5,
                'order' => 4,
                'name'  => 'Literature',
                'slug'  => 'literature',
            ],
            [
                'id'    => 6,
                'order' => 5,
                'name'  => 'Medicine',
                'slug'  => 'medicine',
            ],
            [
                'id'    => 7,
                'order' => 6,
                'name'  => 'Nature',
                'slug'  => 'nature',
            ],
            [
                'id'    => 8,
                'order' => 7,
                'name'  => 'Social Science',
                'slug'  => 'social-science',
            ],
        ];

        Topic::insert($topics);
    }
}
