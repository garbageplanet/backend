<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Trash;
use JWTAuth;
use DB;
use Carbon\Carbon;

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
        return $trashes;
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
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($data['marked_at'] )) {
            $data['marked_at'] = Carbon::now()->toDateString();
        }
       
        $trash = $user->markedTrashes()->create($data); 
        //save point
        $trash->makePoint();
        //save tags TODO

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
       //find id
       $trash = Trash::findOrFail($id);

       //update request
       $trash->update($request->all());

       //remove tags
       $trash->tags()->detach();
       //attach tags
       $trash->tags()->attach($request->input('tags'));
       //return json
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
        $trash->delete();
        
        return true;
    }
}
