<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Trash;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class TrashesController extends Controller
{
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

        return response()->json($trashesArray, 200);
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
            WHERE trashes.geom && ST_MakeEnvelope(?, ?, ?, ?)', 
            [$sw_lat, $sw_lng, $ne_lat, $ne_lng]);

        //long route to do this
        return $trashes;
        $trashesArray= [];
        foreach ($trashes as $trash) {
            $array = $trash->toArray();
            $array['types'] = $trash->types->pluck('type')->toArray();
            $trashesArray[] = $array;
        }

        return response()->json($trashesArray, 200);
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
        if (!isset($data['marked_at'] )) {
            $data['marked_at'] = Carbon::now()->toDateString();
        }
       
        $trash = Auth::user()->markedTrashes()->create($data); 
        $trash->makePoint();
        $trash->addTypes($request->types); 
        
        //long route to do this
        $array = $trash->toArray();
        $array['types'] = $trash->types->pluck('type')->toArray();

        return response()->json($array, 200);
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

        return response()->json($array, 200);
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

        return response()->json($array, 200);
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
