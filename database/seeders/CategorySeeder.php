<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Musique',
                'icon' => 'fas fa-music',
                'description' => 'Concerts, festivals, DJ sets et plus encore.',
            ],
            [
                'name' => 'Gastronomie',
                'icon' => 'fas fa-utensils',
                'description' => 'Festivals culinaires, dégustations, ateliers de cuisine et plus encore.',
            ],
            [
                'name' => 'Spectacles',
                'icon' => 'fas fa-theater-masks',
                'description' => 'Pièces de théâtre, concerts, spectacles de danse et plus encore.',
            ],
            [
                'name' => 'Sports',
                'icon' => 'fas fa-running',
                'description' => 'Matches, courses, événements sportifs et plus encore.',
            ],
            [
                'name' => 'Conférences',
                'icon' => 'fas fa-chalkboard-teacher',
                'description' => 'Ateliers, séminaires, webinaires et plus encore.',
            ],
            [
                'name' => 'Familles',
                'icon' => 'fas fa-child',
                'description' => 'Spectacles, activités, ateliers et plus encore pour toute la famille.',
            ],
        ];

        foreach ($categories as $cat) {
            category::create($cat);
        }
    }
}
