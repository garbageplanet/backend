<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Litter;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class LittersController extends Controller
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
        $litters = Litter::all();

        //long route to do this
        //dd($litters);
        $littersArray= [];
        foreach ($litters as $litter) {
            $array = $litter->toArray();
            $array['types'] = $litter->types->pluck('type')->toArray();
            $littersArray[] = $array;
        }

        $litters = collect($littersArray);
        return $litters;
        // return response()->json($littersArray, 200)->header('Access-Control-Allow-Origin', '*');

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
                
        $query = "SELECT * FROM litters WHERE litters.geom && ST_MakeEnvelope($bounds)";
        
        $litters = DB::select($query);

        //get id's of the litters
        $litter_ids = [];
        foreach ($litters as $litter) {
            $litter_ids[] = $litter->id;
        }
        $litters = Litter::whereIn('id', $litter_ids)->get();

        $littersArray= [];
        foreach ($litters as $litter) {
            $array = $litter->toArray();
            $array['types'] = $litter->types->pluck('type')->toArray();
            $littersArray[] = $array;
        }

        $litters = collect($littersArray);
        return $litters;
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
        $litter = Auth::user()->markedLitters()->create($data);
        
        //Skip this for now
        $litter->makeLine();
        
        $litter->addTypes($request->types);
        //long route to do this
        $array = $litter->toArray();
        $array['types'] = $litter->types->pluck('type')->toArray();

        $litter = collect($array);

        return $litter;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $litter = Litter::findOrFail($id);
        //long route to do this
        $array = $litter->toArray();
        $array['types'] = $litter->types->pluck('type')->toArray();
        $litter = collect($array);
        return $litter;
    }
    
    public function confirm(Request $request, $id)
    {
        $litter = Litter::findOrFail($id);
        
        $litter->confirm($id);
        
        if($litter->save()) {
            $returnData = $litter->find($litter->id)->toArray();
            $data = array ("message" => "litter updated","data" => $returnData );
            return response()->json(["data" => $data], 200);            
        } 
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
        $litter = Litter::findOrFail($id);

        //update request
        $litter->update($request->all());
        //delete types
        $litter->types()->delete();
        //add new types
        $litter->addTypes($request->types);

        $array = $litter->toArray();
        $array['types'] = $litter->types->pluck('type')->toArray();

        $litter = collect($array);
        return $litter;
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
        $litter = Litter::findOrFail($id);
        //delete
        $litter->types()->delete();
        $litter->delete();
        //delete types

        return response()->json("{}", 200);

    }
}
