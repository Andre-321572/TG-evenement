<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;


class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Comment puis-je créer un compte ?',
                'answer' => 'Vous pouvez créer un compte en cliquant sur le bouton "S\'inscrire" en haut de la page et en remplissant le formulaire d\'inscription.',
                'order' => 1,
            ],
            [
                'question' => 'Comment puis-je réinitialiser mon mot de passe ?',
                'answer' => 'Sur la page de connexion, cliquez sur "Mot de passe oublié ?" et suivez les instructions pour réinitialiser votre mot de passe.',
                'order' => 2,
            ],
            // Ajoutez d'autres FAQs ici
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
