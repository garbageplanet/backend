<?php

namespace App\Http\Controllers\Trashes;

use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Trash;
use App\Models\User;
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

        foreach ( $trashes as $trash ) {
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
        $bounds = str_replace(",", ", ", $request->bounds);

        $query = "SELECT * FROM trashes WHERE trashes.geom && ST_MakeEnvelope($bounds)";

        $trashes = DB::select($query);

        Log::debug(print_r($trashes, true));

        $trash_ids = [];

        foreach ($trashes as $trash) {
            $trash_ids[] = $trash->id;
        }

        Log::debug(print_r($trash_ids, true));

        $trashes = Trash::whereIn('id', $trash_ids)->get();

        Log::debug(print_r($trashes, true));

        $trashesArray= [];

        foreach ($trashes as $trash) {
            $array = $trash->toArray();
            $array['types'] = $trash->types->pluck('type')->toArray();
            $trashesArray[] = $array;
        }

        $trashes = collect($trashesArray);

        Log::debug(print_r($trashesArray, true));

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
        $data = $request->all();

        Log::debug(print_r($data, true));

        if (!Auth::check()) {
            $glome = Glome::createGlomeAccount();
            $user = User::create(['email' => $glome, 'password' => env('GLOME_PASSWORD','12345678'), 'name' => $glome]);
            Auth::attempt(['email' => $glome, 'password' => env('GLOME_PASSWORD','12345678')]);
        }

        $validator = $this->validate($request, [
              'amount'      => 'alpha_num|required|max:1'
            , 'todo'        => 'alpha_num|nullable|max:1'
            , 'image_url'   => 'url|nullable'
            , 'confirms'    => 'nullable'
            , 'cleaned'     => 'boolean|nullable'
            , 'tags'        => 'alpha_num|nullable'
            , 'tweetonsave' => 'boolean|nullable'
            , 'latlng'      => array(
                    'required'
                  , 'max:60'
                  , 'regex:/^([-+]?\d{1,2}[.]\d+)\s*,\s*([-+]?\d{1,3}[.]\d+)$/u'
            )
            , 'tags' => array(
                    'max:60'
                  , 'nullable'
                  , 'regex:/^[\p{L}\p{N}\040,.-]+$/'
            )
            , 'note' => array(
                    'max:140'
                  , 'nullable'
                  , 'min:10'
                  , 'regex:/^[\p{L}\p{N}\040,.-]+$/'
            )
            , 'type' => array(
                   'max:140'
                  , 'nullable'
                  , 'regex:/^[\p{L}\p{N}\040,.-]+$/'
            )
            , 'embed' => array(
                    'max:10'
                  , 'nullable'
                  , 'regex:/^[0-9\040,]+$/u'
            )
            , 'sizes' => array(
                    'max:10'
                  , 'nullable'
                  , 'regex:/^[0-9\040,]+$/u'
            )
        ]);

        $trash = Auth::user()->markedTrashes()->create($data);

        $trash->makePoint();

        // Tweet the new feature
        // if ( $data->tweetonsave )
        // {
        //     $trash->tweet();
        // }

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

    public function clean(Request $request, $id)
    {

        $trash = Trash::findOrFail($id);

        $trash->clean($id);

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
        // $trash->types()->delete();

        $trash->delete();

        return response()->json("{}", 200);

    }
}
