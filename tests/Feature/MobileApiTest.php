<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Evenement;
use App\Models\Billet;
use App\Models\TicketCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de l'inscription via API.
     */
    public function test_api_registration()
    {
        $response = $this->postJson('/api/auth/register', [
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'testapi@tgevent.com',
            'phone' => '88888888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testapi@tgevent.com',
        ]);
    }

    /**
     * Test de la connexion via API.
     */
    public function test_api_login()
    {
        // Créer un utilisateur
        $user = User::create([
            'nom' => 'Login',
            'prenom' => 'User',
            'email' => 'loginapi@tgevent.com',
            'phone' => '77777777',
            'password' => bcrypt('password123'),
            'role' => 'utilisateur',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'login' => 'loginapi@tgevent.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
                'token',
            ]);
    }

    /**
     * Test de la récupération de la liste des événements.
     */
    public function test_api_fetch_events()
    {
        // Créer un organisateur
        $organizer = User::create([
            'nom' => 'Organizer',
            'prenom' => 'User',
            'email' => 'org@tgevent.com',
            'phone' => '11111111',
            'password' => bcrypt('password123'),
            'role' => 'utilisateur',
        ]);

        // Créer un événement publié
        $event = Evenement::create([
            'categorie' => 'Concert',
            'titre' => 'Mega Concert Test',
            'date' => now()->addDays(5)->toDateString(),
            'start_heure' => '18:00',
            'end_heure' => '22:00',
            'lieu' => 'Stade Municipal',
            'nom_proprietaire' => 'Promo Plus',
            'telephone' => '12345678',
            'email' => 'promo@test.com',
            'statut' => 'publier',
            'user_id' => $organizer->id,
        ]);

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'titre' => 'Mega Concert Test',
            ]);
    }

    /**
     * Test de la vérification de ticket par le scanner.
     */
    public function test_api_scanner_verify_ticket()
    {
        // Créer l'organisateur, le scanner et le participant
        $organizer = User::create([
            'nom' => 'Org', 'prenom' => 'User', 'email' => 'org2@tgevent.com', 'phone' => '12121212', 'password' => bcrypt('password123')
        ]);
        
        $scanner = User::create([
            'nom' => 'Scan', 'prenom' => 'User', 'email' => 'scanner@tgevent.com', 'phone' => '13131313', 'password' => bcrypt('password123'), 'role' => 'scanner'
        ]);

        $event = Evenement::create([
            'categorie' => 'Festival', 'titre' => 'Fest', 'date' => now()->addDays(5)->toDateString(), 'start_heure' => '14:00', 'end_heure' => '23:00',
            'lieu' => 'Plage', 'nom_proprietaire' => 'Mairie', 'telephone' => '12345678', 'email' => 'mairie@test.com', 'statut' => 'publier', 'user_id' => $organizer->id,
        ]);

        $ticketType = Billet::create([
            'type' => 'Standard', 'prix' => 5000, 'quantite' => 100, 'quantite_totale' => 100, 'quantite_disponible' => 100, 'evenement_id' => $event->id
        ]);

        $ticket = TicketCode::create([
            'code' => 'TGE-TEST999', 'evenement_id' => $event->id, 'billet_id' => $ticketType->id, 'buyer_email' => 'buyer@test.com', 'buyer_name' => 'John Buyer', 'is_scanned' => false
        ]);

        // Authentifier le scanner
        $this->actingAs($scanner, 'sanctum');

        // Vérifier le ticket
        $response = $this->postJson('/api/scanner/verify', [
            'code' => 'TGE-TEST999',
            'evenement_id' => $event->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'valid',
                'message' => 'Accès autorisé !',
            ]);

        // Vérifier dans la base que le ticket est marqué scanné
        $this->assertTrue(TicketCode::find($ticket->id)->is_scanned);
    }
}
