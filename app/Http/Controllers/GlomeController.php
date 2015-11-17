<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Glome;

class GlomeController extends Controller
{
    public function create()
    {
        $glome = Glome::createGlomeAccount();
        return $glome;
    }

    public function show($glomeid)
    {
        $glome = Glome::showGlomeAccount($glomeid);
        return $glome;
    }
}
