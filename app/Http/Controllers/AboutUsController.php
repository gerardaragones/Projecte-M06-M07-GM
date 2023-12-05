<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $teamMembers = [
            [
                'name' => 'Nombre1',
                'position' => 'Cargo1',
                'image' => '/ruta/a/la/foto1.jpg',
            ],
            [
                'name' => 'Nombre2',
                'position' => 'Cargo2',
                'image' => '/ruta/a/la/foto2.jpg',
            ],
        ];

        return view('about-us', compact('teamMembers'));
    }
}