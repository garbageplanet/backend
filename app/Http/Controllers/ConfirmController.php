<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Trash;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class ConfirmController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['store', 'update']]);
    }

    public function add()
    {
      // TODO increments counts on user input to confirm litter and trash
    }



 public function confirmTrash(Request $request, $id, $featuretype)
 {
   
      $trash = Trash::findOrFail($id);

      $trash->confirm += 1;

      $trash->save();

      return $trash;  

 }

 public function confirmLitter(Request $request, $id, $featuretype)
 {

      $litter = Litter::findOrFail($id);

      $litter->confirm += 1;

      $litter->save();

      return $litter;  

  }

  
}