<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Marie',
                'location' => 'Lomé',
                'content' => 'TGevent est la plateforme idéale pour trouver des événements qui me correspondent. J\'ai pu découvrir de nombreux événements géniaux que je n\'aurais jamais connus autrement.',
                'image' => 'asset/Aimg/user-33638_640.webp',
            ],
            [
                'name' => 'Jean Durand',
                'location' => 'Lyon',
                'content' => 'TGevent est une plateforme facile à utiliser et très intuitive. J\'ai pu trouver et réserver des billets pour des événements en quelques clics.',
                'image' => 'asset/Aimg/user-default.jpg',
            ],
            [
                'name' => 'Sarah Dubois',
                'location' => 'Marseille',
                'content' => 'TGevent est une plateforme qui propose une grande variété d\'événements à des prix abordables. J\'y ai trouvé des événements pour tous les goûts et tous les budgets.',
                'image' => 'asset/Aimg/user-default.jpg',
            ],
            [
                'name' => 'Pierre Martin',
                'location' => 'Bordeaux',
                'content' => 'TGevent est une plateforme très fiable. J\'ai toujours reçu mes billets à temps et les événements auxquels j\'ai participé étaient tous de grande qualité.',
                'image' => 'asset/Aimg/user-default.jpg',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
