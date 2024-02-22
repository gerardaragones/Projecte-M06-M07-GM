<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;

class TokenTest extends TestCase
{
    public static object $testUser;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Crear usuario de prueba (guardar en BD más tarde)
        $name = "test_" . time();
        self::$testUser = (object) [
            "name"      => "{$name}",
            "email"     => "{$name}@mailinator.com",
            "password"  => "12345678"
        ];
    }

    public function test_register()
    {
        // Crear usuario usando el servicio web API
        $response = $this->postJson('api/register', [
            "name"      => self::$testUser->name,
            "email"     => self::$testUser->email,
            "password"  => self::$testUser->password,
        ]);

        // Verificar respuesta
        $response->assertOk();
        // Verificar respuesta TOKEN
        $this->_test_token($response);
    }

    public function test_register_error()
    {
        // Crear usuario usando el servicio web API con datos inválidos
        $response = $this->postJson('/api/register', [
            "name"      => "",
            "email"     => "mailinator.com",
            "password"  => "12345678",
        ]);
        // Verificar respuesta
        $response->assertStatus(422);
        // Verificar errores de validación
        $response->assertInvalid(["name"]);
        $response->assertInvalid(["email"]);
    }

    /**
     * @depends test_register
     */
    public function test_login()
    {
        $user = self::$testUser;
        // Iniciar sesión usando el servicio web API
        $response = $this->postJson('/api/login', [
            "email"     => $user->email,
            "password"  => $user->password,
        ]);
        // Verificar respuesta
        $response->assertOk();
        // Verificar TOKEN de respuesta
        $this->_test_token($response);
    }

    public function test_login_invalid()
    {
        // Iniciar sesión usando el servicio web API con credenciales inválidas
        $response = $this->postJson('/api/login', [
            "email"     => "notexists@mailinator.com",
            "password"  => "12345678",
        ]);
        // Verificar respuesta
        $response->assertStatus(401);
        // Verificar propiedades JSON
        $response->assertJson([
            "success" => false,
            "message" => true, // cualquier valor
        ]);
    }

    /**
     * @depends test_register
     */
    public function test_logout()
    {
        $user = User::factory()->create(); // Crear un usuario en la base de datos
    
        Sanctum::actingAs($user); // Autenticar al usuario
    
        // Cerrar sesión usando el servicio web API
        $response = $this->postJson('/api/logout');
    
        // Verificar respuesta
        $response->assertOk();
    
        // Verificar propiedades JSON
        $response->assertJson([
            "success" => false,
            "message" => "User logged out unauthorized",
        ]);
    }

    public function test_logout_unauthorized()
    {
        // Cerrar sesión usando el servicio web API
        $response = $this->postJson('/api/logout');
        // Verificar respuesta
        $response->assertStatus(401);
        // Verificar propiedades JSON
        $response->assertJson([
            "message" => true, // cualquier valor
        ]);
    }

    /**
     * @depends test_register
     */
    public function test_user()
    {
        $user = new User((array)self::$testUser);
        Sanctum::actingAs(
            $user,
            ['*'] // otorgar todos los permisos al token
        );
        // Obtener datos de usuario usando el servicio web API
        $response = $this->getJson('/api/user');
        // Verificar respuesta
        $response->assertOk();
        // Verificar propiedades JSON
        $response->assertJson([
            "success" => true,
            "user"    => true, // cualquier valor
        ]);
        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->where("user.name", $user->name)
                    ->where("user.email", $user->email)
                    ->missing("user.password")
                    ->where('roles', ['author'])
                    ->etc()
        );
    }

    public function test_user_unauthorized()
    {
        // Obtener datos de usuario usando el servicio web API
        $response = $this->getJson('/api/user');
        // Verificar respuesta
        $response->assertStatus(401);
        // Verificar propiedades JSON
        $response->assertJson([
            "message" => true, // cualquier valor
        ]);
    }

    protected function _test_token($response)
    {
        // Verificar propiedades JSON
        $response->assertJson([
            "success"   => true,
            "authToken" => true, // cualquier valor
            "tokenType" => true, // cualquier valor
        ]);
        // Verificar valores dinámicos JSON
        $response->assertJsonPath("authToken",
            fn ($authToken) => !empty($authToken)
        );
        // Verificar valores exactos JSON
        $response->assertJsonPath("tokenType", "Bearer");
    }
}
