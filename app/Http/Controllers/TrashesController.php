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

class TrashesController extends Controller
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
        $trashes = Trash::all();

        //long route to do this
        //dd($trashes);
        $trashesArray= [];
        foreach ($trashes as $trash) {
            $array = $trash->toArray();
            $array['types'] = $trash->types->pluck('type')->toArray();
            $trashesArray[] = $array;
        }

        $trashes = collect($trashesArray);
        return $trashes;
        //return response()->json($trashesArray, 200)->header('Access-Control-Allow-Origin', '*');
        
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
    

        $trashes = DB::select('
            SELECT *
            FROM trashes
        
            WHERE trashes.geom && ST_MakeEnvelope(?, ?, ?, ?)'
            , 
            [$sw_lat, $sw_lng, $ne_lat, $ne_lng]);

        //get id's of the trashes
        $trash_ids = [];
        foreach ($trashes as $trash) {
            $trash_ids[] = $trash->id;
        }
        $trashes = Trash::whereIn('id', $trash_ids)->get();
        
        $trashesArray= [];
        foreach ($trashes as $trash) {
            $array = $trash->toArray();
            $array['types'] = $trash->types->pluck('type')->toArray();
            $trashesArray[] = $array;
        }

        $trashes = collect($trashesArray);
        return $trashes;        
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
            $user = User::create('email' => $glome, 'password' => '12345678', 'name' => $glome);
            Auth::attempt(['email' => $glome, 'password' => '12345678']));
        }
        $trash = Auth::user()->markedTrashes()->create($data); 
        $trash->makePoint();
        $trash->addTypes($request->types); 
        if ($trash->amount > 3) {
            $trash->notifyHelsinkiAboutTheTrash();
        }
        //long route to do this
        $array = $trash->toArray();
        $array['types'] = $trash->types->pluck('type')->toArray();

        $trash = collect($array);

        return $trash;
    }

    public function storeWithoutUser(Request $request)
    {
        //manually parse token because its optional
        if ($request->header('Authorization')) {
            $user = JWTAuth::parseToken()->authenticate();
        } 
        else {
            $user = User::first();
        }

        $data = $request->all();
        $data['marked_by'] = $user->id;
        if (!isset($data['amount']) ){
            $data['amount'] = 0;
        }
        
        
        $trash = Trash::create($data);
        $trash->makePoint();
        //types
        if (isset($data['types'])) {
            $trash->addTypes($data['types']); 
        }
        if ($trash->amount > 3) {
            $trash->notifyHelsinkiAboutTheTrash();
        }
        
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
        $trash = Trash::findOrFail($id);
        //long route to do this
        $array = $trash->toArray();
        $array['types'] = $trash->types->pluck('type')->toArray();
        $trash = collect($array);
        return $trash;
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
        $trash = Trash::findOrFail($id);

        //update request
        $trash->update($request->all());
        //delete types
        $trash->types()->delete();
        //add new types
        $trash->addTypes($request->types); 

        $array = $trash->toArray();
        $array['types'] = $trash->types->pluck('type')->toArray();

        $trash = collect($array);
        return $trash;
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
        $trash = Trash::findOrFail($id);
        //delete
        $trash->types()->delete();
        $trash->delete();
        //delete types
        
        return response()->json("{}", 200);
    
    }
}
