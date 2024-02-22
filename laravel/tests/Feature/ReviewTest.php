<?php


namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;


class ReviewTest extends TestCase
{
    public static array $testUser = [];
    public static User $testUserObj;
    public static array $validData = [];
    public static array $invalidData = [];

    public static function setUpBeforeClass() : void
    {
        parent::setUpBeforeClass();
        // Creem usuari/a de prova
        $name = "test_" . time();
        self::$testUser = [
            "name"      => "{$name}",
            "email"     => "{$name}@mailinator.com",
            "password"  => "12345678"
        ];


        // TODO Omplir amb dades vàlides
        self::$validData = [];
        self::$invalidData = [];
    }

    public function test_place_first()
    {
        
        $user = new User(self::$testUser);
        $user->save();
        self::$testUserObj = $user;
        
        $this->assertDatabaseHas('users', [
            'email' => self::$testUser['email'],
        ]);
    }

   public function test_place_create() : object
   {
       // Create fake file

       Sanctum::actingAs(self::$testUserObj);
       $name  = "avatar.png";
       $size = 500; /*KB*/
       $upload = UploadedFile::fake()->image($name)->size($size);
       $name = "prueba";
       $description = "description";
       $latitude = 1;
       $longitude = 1;
       // Upload fake file using API web service
       $response = $this->postJson("/api/places", [
           "upload" => $upload,
           "name" => $name,
           "description" => $description,
           "latitude" => $latitude,
           "longitude" => $longitude
       ]);
       // Check OK response
       $this->_test_ok($response, 201);
       // Check validation errors
       $response->assertValid(["upload", "name", "description", "latitude", "longitude"]);
       // Check JSON exact values
       // Check JSON dynamic values
       $response->assertJsonPath("data.id",
           fn ($id) => !empty($id)
       );
       // Read, update and delete dependency!!!
       $json = $response->getData();
       return $json->data;
   }
   /**
    * @depends test_place_create
    */
    public function test_review_create(object $place) : object
    {
        // Create fake review data
        Sanctum::actingAs(self::$testUserObj);
        $comment = "definitivamente una review";
        $rating = 5; // Por ejemplo, calificación de 5 estrellas
    
        // Enviar la solicitud para crear la reseña
        $response = $this->postJson("/api/places/{$place->id}/reviews", [
            "comment" => $comment,
            "rating" => $rating
        ]);
    
        // Verificar que la solicitud se procesó correctamente
        $this->_test_ok($response, 201);
    
        // Verificar que los campos requeridos están presentes en la respuesta JSON
        $response->assertValid(["author_id", "place_id", "comment", "rating"]);
    
        // Verificar que la respuesta contiene un ID de reseña
        $response->assertJsonPath("data.id", fn ($id) => !empty($id));
    
        // Obtener los datos de la respuesta JSON
        $json = $response->getData();
    
        // Devolver los datos de la reseña creada
        return $json->data;
    }
    

   /**
    * @depends test_place_create
    */
   public function test_review_list(object $place)
   {
       // Read one file

       Sanctum::actingAs(new User(self::$testUser));
       $response = $this->getJson("/api/places/{$place->id}/reviews");
       // Check OK response
       $this->_test_ok($response);
       // Check JSON exact values
       $response->assertValid(["author_id", "place_id", "body"]);
   }

    /**
    * @depends test_place_create
    * @depends test_review_create
    */
    public function test_review_delete(object $place, object $review)
    {
        // Delete one file using API web service

        Sanctum::actingAs(new User(self::$testUser));

        
        $response = $this->deleteJson("/api/places/{$place->id}/reviews/{$review->id}");
        // Check OK response
        $this->_test_ok($response);
    }

   /**
    * @depends test_place_create
    */
   public function test_place_delete(object $place)
   {
       // Delete one file using API web service

       Sanctum::actingAs(new User(self::$testUser));
       $response = $this->deleteJson("/api/places/{$place->id}");
       // Check OK response
       $this->_test_ok($response);
   }


   public function test_place_last()
   {
       // Eliminem l'usuari al darrer test
       $user = self::$testUserObj;
       $user->delete();
       // Comprovem que s'ha eliminat
       $this->assertDatabaseMissing('users', [
           'email' => self::$testUser['email'],
       ]);
   }

   protected function _test_ok($response, $status = 200)
   {
       // Check JSON response status
       $response->assertStatus($status);
       
       // Check if the response is successful
       $response->assertJson([
           "success" => true,
       ]);
       
       // Check if the response contains data (if applicable)
       if ($response->json("success")) {
           $response->assertJsonMissing([
               "errors", // Si hay errores, no se espera que haya datos
           ]);
       }
   }


   protected function _test_error($response)
   {
       // Check response
       $response->assertStatus(422);
       // Check validation errors
       $response->assertInvalid(["upload"]);
       // Check JSON properties
       $response->assertJson([
           "message" => true, // any value
           "errors"  => true, // any value
       ]);       
       // Check JSON dynamic values
       $response->assertJsonPath("message",
           fn ($message) => !empty($message) && is_string($message)
       );
       $response->assertJsonPath("errors",
           fn ($errors) => is_array($errors)
       );
   }

   protected function _test_notfound($response)
   {
       // Check JSON response
       $response->assertStatus(404);
       // Check JSON properties
       $response->assertJson([
           "success" => false,
           "message" => true // any value
       ]);
       // Check JSON dynamic values
       $response->assertJsonPath("message",
           fn ($message) => !empty($message) && is_string($message)
       );       
   }
}