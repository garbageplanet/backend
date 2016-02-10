<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Trash;
use App\Clean;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class CleanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['store', 'update']]);
    }

    public function toggle()
    {
      // TODO toggle the cleaned field on litter_id or trash_id
    }

}
