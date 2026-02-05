<?php

namespace Tests\Feature;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientRegistrationTest extends TestCase
{
    // use RefreshDatabase; // Be careful with this on persistent DB, but it's usually safer for tests. Checking if user allows it. 
    // User is on XAMPP, likely persistent DB. I won't use RefreshDatabase to avoid wiping their DB. I'll delete created user manually.

    public function test_client_registration_saves_data_correctly()
    {
        $input = [
            'nombre_completo' => 'Test Client Feature',
            'email' => 'testfeature' . time() . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'cliente',
            'direccion' => 'Calle Feature 123',
            'telefono' => '123456789',
        ];

        $creator = new CreateNewUser();
        $user = $creator->create($input);

        $this->assertDatabaseHas('usuario', [
            'email' => $input['email'],
        ]);

        $this->assertDatabaseHas('cliente', [
            'id_usuario' => $user->id_usuario,
            'direccion' => 'Calle Feature 123',
            'telefono' => '123456789',
        ]);

        // Clean up
        $user->delete();
    }
}
