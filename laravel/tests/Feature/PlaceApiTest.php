<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;

class PlaceApiTest extends TestCase
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

        self::$validData = [];
        
        self::$invalidData = [];
    }

    public function test_place_first()
    {
        // Desem l'usuari al primer test
        $user = new User(self::$testUser);
        $user->save();
        // NEW!!!!!
        self::$testUserObj = $user;
        // Comprovem que s'ha creat
        $this->assertDatabaseHas('users', [
            'email' => self::$testUser['email'],
        ]);
    }

    public function test_place_list()
   {
        Sanctum::actingAs(new User(self::$testUser));
        $response = $this->getJson("/api/places");
       
       $this->_test_ok($response);
   }


   public function test_place_create() : object
   {
       // Create fake file
       Sanctum::actingAs(self::$testUserObj);
       $name  = "avatar.png";
       $size = 500; /*KB*/
       $upload = UploadedFile::fake()->image($name)->size($size);
       $name = "testOscar";
       $description = "testOscar";
       $latitude = 12323;
       $longitude = 12323;
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
       // Check JSON dynamic values
       $response->assertJsonPath("data.id",
           fn ($id) => !empty($id)
       );
       // Read, update and delete dependency!!!
       $json = $response->getData();
       return $json->data;
   }


   public function test_place_create_error()
   {
       Sanctum::actingAs(new User(self::$testUser));
       $name  = "avatar.png";
       $size = 5000; /*KB*/
       $upload = UploadedFile::fake()->image($name)->size($size);
       $name = "testOscar";
       $description = "testOscar";
       $latitude = 23232122;
       $longitude = 1232323;
       // Upload fake file using API web service
       $response = $this->postJson("/api/places", [
        "upload" => $upload,
        "name" => $name,
        "description" => $description,
        "latitude" => $latitude,
        "longitude" => $longitude
    ]);
       // Check ERROR response
       $this->_test_error($response);
   }


   /**
    * @depends test_place_create
    */
   public function test_place_read(object $place)
   {
       // Read one file
       Sanctum::actingAs(new User(self::$testUser));
       $response = $this->getJson("/api/places/{$place->id}");
       // Check OK response
       $this->_test_ok($response);
       // Check JSON exact values
       $response->assertValid(["upload", "name", "description", "latitude", "longitude"]);
       
   }

   public function test_place_read_notfound()
   {    
        Sanctum::actingAs(new User(self::$testUser));
       $id = "not_exists";
       $response = $this->getJson("/api/places/{$id}");
       $this->_test_notfound($response);
   }


   /**
    * @depends test_place_create
    */
   public function test_place_update(object $place)
   {
       // Create fake file

       Sanctum::actingAs(new User(self::$testUser));
       $name  = "photo.jpg";
       $size = 1000; /*KB*/
       $upload = UploadedFile::fake()->image($name)->size($size);
       // Upload fake file using API web service
       $name = "testOscar";
       $description = "testOscar";
       $latitude = 12323;
       $longitude = 32323;
       $response = $this->putJson("/api/places/{$place->id}", [
           "upload" => $upload,
           "name" => $name,
           "description" => $description,
           "latitude" => $latitude,
           "longitude" => $longitude
       ]);
       // Check OK response
       $this->_test_ok($response);
       // Check validation errors
       $response->assertValid(["upload", "name", "description", "latitude", "longitude"]);
   }


   /**
    * @depends test_place_create
    */
   public function test_place_update_error(object $place)
   {
       // Create fake file with invalid max size

       Sanctum::actingAs(new User(self::$testUser));
       $name  = "photo.jpg";
       $size = 3000; /*KB*/
       $upload = UploadedFile::fake()->image($name)->size($size);
       // Upload fake file using API web service
       $name = "testOscar";
       $description = "testOscar";
       $latitude = 1232323;
       $longitude = 1232322;
       $response = $this->putJson("/api/places/{$place->id}", [
           "upload" => $upload,
           "name" => $name,
           "description" => $description,
           "latitude" => $latitude,
           "longitude" => $longitude
       ]);
       // Check ERROR response
       $this->_test_error($response);
   }


   public function test_place_update_notfound()
    {
        Sanctum::actingAs(new User(self::$testUser));
        $id = "not_exists";
        $response = $this->putJson("/api/places/{$id}", []);
        $this->_test_notfound($response);
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


   public function test_place_delete_notfound()
   {
        Sanctum::actingAs(new User(self::$testUser));
       $id = "not_exists";
       $response = $this->deleteJson("/api/places/{$id}");
       $this->_test_notfound($response);
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
       // Check JSON response
       $response->assertStatus($status);
       // Check JSON properties
       $response->assertJson([
           "success" => true,
       ]);
       // Check JSON dynamic values
       $response->assertJsonPath("data",
           fn ($data) => is_array($data)
       );
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