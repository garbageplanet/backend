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
        $this->middleware('jwt.auth', ['only' => ['store', 'update']]);
    }

    public function add()
    {
      // NOTE This controller is for counting the users who join a cleaning event
      // TODO add an integer to the 'joined' field on cleaning_id
    }

}
