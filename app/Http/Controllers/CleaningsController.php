<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Cleaning;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class CleaningsController extends Controller
{
    public function __construct()
    {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cleanings = Cleaning::all();

        //long route to do this
        //dd($trashes);
        $cleaningsArray= [];
        foreach ($cleanings as $cleaning) {
            $array = $cleaning->toArray();
            $cleaningsArray[] = $array;
        }

        $cleanings = collect($cleaningsArray);
        return $cleanings;
        // return response()->json($cleaningsArray, 200)->header('Access-Control-Allow-Origin', '*');

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function withinBounds(Request $request)
    {
        //TODO: Validate (regex validation to bounds)
        //
        // parse bounds

        $coordinates = explode(", ", $request->bounds);
        $sw_lat = $coordinates[2];
        $sw_lng = $coordinates[3];
        $ne_lat = $coordinates[0];
        $ne_lng = $coordinates[1];

        $cleanings = DB::select('
            SELECT *
            FROM cleanings

            WHERE cleanings.geom && ST_MakeEnvelope(?, ?, ?, ?)'
            ,
            [$sw_lat, $sw_lng, $ne_lat, $ne_lng]);

        //get id's of the cleanings
        $cleaning_ids = [];
        foreach ($cleanings as $cleaning) {
            $cleaning_ids[] = $cleaning->id;
        }
        $cleanings = Cleaning::whereIn('id', $cleaning_ids)->get();

        $cleaningsArray= [];
        foreach ($cleanings as $cleaning) {
            $array = $cleaning->toArray();
            $cleaningsArray[] = $array;
        }

        $cleanings = collect($cleaningsArray);
        return $cleanings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all(); //can be changed to request->only('first', 'second');
        //$user = JWTAuth::parseToken()->authenticate();
        if (!Auth::check()) {
            $glome = Glome::createGlomeAccount();
            $user = User::create(['email' => $glome, 'password' => '12345678', 'name' => $glome]);
            Auth::attempt(['email' => $glome, 'password' => '12345678']);
        }
        $cleaning = Auth::user()->createdCleanings()->create($data);
        $cleaning->makePoint();
        $cleaning->addDate($request->date);
        //long route to do this
        $array = $cleaning->toArray();

        $cleaning = collect($array);

        return $trash;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $cleaning = Cleaning::findOrFail($id);
        //long route to do this
        $array = $cleaning->toArray();
        $cleaning = collect($array);
        return $cleaning;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //currently anyone authenticated user can update anything
        //find id
        $cleaning = Cleaning::findOrFail($id);

        //update request
        $cleaning->update($request->all());

        $array = $cleaning->toArray();

        $cleaning = collect($array);
        return $cleaning;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //find id
        $cleaning = Cleaning::findOrFail($id);
        //delete
        $cleanings->delete();
        //delete types

        return response()->json("{}", 200);

    }
}
