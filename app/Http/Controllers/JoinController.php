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

class JoinController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['store', 'update', 'destroy']]);
    }

    public function add()
    {
      // TODO add an integer to the 'joined' field on cleaning_id
    }

}
