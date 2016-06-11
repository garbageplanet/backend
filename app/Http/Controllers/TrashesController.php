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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function withinBounds(Request $request)
    {
        // parse bounds        
        $bounds = str_replace(",", ", ", $request->bounds);
                
        $query = "SELECT * FROM trashes WHERE trashes.geom && ST_MakeEnvelope($bounds)";
        
        $trashes = DB::select($query);
        
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
            $user = User::create(['email' => $glome, 'password' => '12345678', 'name' => $glome]);
            Auth::attempt(['email' => $glome, 'password' => '12345678']);
        }
        
        $trash = Auth::user()->markedTrashes()->create($data);
        
        // $trash = Trash::create($data);
        $trash->makePoint();        
        
        // Add types
        $trash->addTypes($request->types);
        
        $array = $trash->toArray();
        
        $array['types'] = $trash->types->pluck('type')->toArray();

        $trash = collect($array);

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
        //delete types, sizes and embeds
        $trash->types()->delete();
        //add new types
        $trash->addTypes($request->types);

        $array = $trash->toArray();
        $array['types'] = $trash->types->pluck('type')->toArray();

        $trash = collect($array);
        return $trash;
    }
    
    public function confirm(Request $request, $id)
    {
        
        $trash = Trash::findOrFail($id);

        $trash->confirm($id);
        
        if($trash->save()) {
            $returnData = $trash->find($trash->id)->toArray();
            $data = array ("message" => "trash updated","data" => $returnData );
            return response()->json(["data" => $data], 200);            
        } 
        
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
        //delete types
        $trash->types()->delete();
        
        $trash->delete();

        return response()->json("{}", 200);

    }
}
