<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'domain_create',
            ],
            [
                'id'    => 18,
                'title' => 'domain_edit',
            ],
            [
                'id'    => 19,
                'title' => 'domain_show',
            ],
            [
                'id'    => 20,
                'title' => 'domain_delete',
            ],
            [
                'id'    => 21,
                'title' => 'domain_access',
            ],
            [
                'id'    => 22,
                'title' => 'question_create',
            ],
            [
                'id'    => 23,
                'title' => 'question_edit',
            ],
            [
                'id'    => 24,
                'title' => 'question_show',
            ],
            [
                'id'    => 25,
                'title' => 'question_delete',
            ],
            [
                'id'    => 26,
                'title' => 'question_access',
            ],
            [
                'id'    => 27,
                'title' => 'country_create',
            ],
            [
                'id'    => 28,
                'title' => 'country_edit',
            ],
            [
                'id'    => 29,
                'title' => 'country_show',
            ],
            [
                'id'    => 30,
                'title' => 'country_delete',
            ],
            [
                'id'    => 31,
                'title' => 'country_access',
            ],
            [
                'id'    => 32,
                'title' => 'project_create',
            ],
            [
                'id'    => 33,
                'title' => 'project_edit',
            ],
            [
                'id'    => 34,
                'title' => 'project_show',
            ],
            [
                'id'    => 35,
                'title' => 'project_delete',
            ],
            [
                'id'    => 36,
                'title' => 'project_access',
            ],
            [
                'id'    => 37,
                'title' => 'answer_create',
            ],
            [
                'id'    => 38,
                'title' => 'answer_edit',
            ],
            [
                'id'    => 39,
                'title' => 'answer_show',
            ],
            [
                'id'    => 40,
                'title' => 'answer_delete',
            ],
            [
                'id'    => 41,
                'title' => 'answer_access',
            ],
            [
                'id'    => 42,
                'title' => 'recommendation_create',
            ],
            [
                'id'    => 43,
                'title' => 'recommendation_edit',
            ],
            [
                'id'    => 44,
                'title' => 'recommendation_show',
            ],
            [
                'id'    => 45,
                'title' => 'recommendation_delete',
            ],
            [
                'id'    => 46,
                'title' => 'recommendation_access',
            ],
            [
                'id'    => 47,
                'title' => 'blocklist_create',
            ],
            [
                'id'    => 48,
                'title' => 'blocklist_edit',
            ],
            [
                'id'    => 49,
                'title' => 'blocklist_show',
            ],
            [
                'id'    => 50,
                'title' => 'blocklist_delete',
            ],
            [
                'id'    => 51,
                'title' => 'blocklist_access',
            ],
            [
                'id'    => 52,
                'title' => 'result_create',
            ],
            [
                'id'    => 53,
                'title' => 'result_edit',
            ],
            [
                'id'    => 54,
                'title' => 'result_show',
            ],
            [
                'id'    => 55,
                'title' => 'result_delete',
            ],
            [
                'id'    => 56,
                'title' => 'result_access',
            ],
            [
                'id'    => 57,
                'title' => 'topic_create',
            ],
            [
                'id'    => 58,
                'title' => 'topic_edit',
            ],
            [
                'id'    => 59,
                'title' => 'topic_show',
            ],
            [
                'id'    => 60,
                'title' => 'topic_delete',
            ],
            [
                'id'    => 61,
                'title' => 'topic_access',
            ],
            [
                'id'    => 62,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
