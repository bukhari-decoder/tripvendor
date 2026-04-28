<?php

namespace Database\Seeders;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        {
            DB::table('pages')->insert([
                [
                    'id' => 61,
                    'name' => 'news',
                    'slug' => 'news',
                    'template_name' => 'light',
                    'custom_link' => NULL,
                    'page_title' => 'News',
                    'meta_title' => 'News',
                    'meta_keywords' => '["news","blogs"]',
                    'meta_description' => 'This is meta news',
                    'meta_image' => NULL,
                    'meta_image_driver' => NULL,
                    'breadcrumb_image' => NULL,
                    'breadcrumb_image_driver' => 'local',
                    'breadcrumb_status' => 0,
                    'status' => 1,
                    'type' => 0,
                    'is_breadcrumb_img' => 1,
                    'created_at' => '2024-03-27 17:46:22',
                    'updated_at' => '2024-03-28 20:04:51'
                ],
                [
                    'id' => 62,
                    'name' => 'destination',
                    'slug' => 'destinations',
                    'template_name' => 'light',
                    'custom_link' => NULL,
                    'page_title' => 'Destinations',
                    'meta_title' => 'Destinations',
                    'meta_keywords' => '["destinations"]',
                    'meta_description' => 'This is meta destinations',
                    'meta_image' => NULL,
                    'meta_image_driver' => NULL,
                    'breadcrumb_image' => NULL,
                    'breadcrumb_image_driver' => 'local',
                    'breadcrumb_status' => 0,
                    'status' => 1,
                    'type' => 0,
                    'is_breadcrumb_img' => 1,
                    'created_at' => '2024-03-27 17:46:22',
                    'updated_at' => '2024-03-28 20:04:51'
                ],
                [
                    'id' => 63,
                    'name' => 'tours',
                    'slug' => 'packages',
                    'template_name' => 'light',
                    'custom_link' => NULL,
                    'page_title' => 'Packages',
                    'meta_title' => 'Packages',
                    'meta_keywords' => '["packages"]',
                    'meta_description' => 'This is meta packages',
                    'meta_image' => NULL,
                    'meta_image_driver' => NULL,
                    'breadcrumb_image' => NULL,
                    'breadcrumb_image_driver' => 'local',
                    'breadcrumb_status' => 0,
                    'status' => 1,
                    'type' => 0,
                    'is_breadcrumb_img' => 1,
                    'created_at' => '2024-03-27 17:46:22',
                    'updated_at' => '2024-03-28 20:04:51'
                ]
            ]);
        }

    }
}
