<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Area;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class AreasController extends Controller
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
        $areas = Area::all();

        //long route to do this
        //dd($areas);
        $areasArray= [];
        foreach ($areas as $area) {
            $array = $area->toArray();
            $array['types'] = $area->types->pluck('type')->toArray();
            $areasArray[] = $array;
        }

        $areas = collect($areasArray);
        return $areas;
        // return response()->json($areasArray, 200)->header('Access-Control-Allow-Origin', '*');

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

        $areas = DB::select('
            SELECT *
            FROM areas

            WHERE areas.geom && ST_MakeEnvelope(?, ?, ?, ?)'
            ,
            [$sw_lat, $sw_lng, $ne_lat, $ne_lng]);

        //get id's of the areas
        $area_ids = [];
        foreach ($areas as $area) {
            $area_ids[] = $area->id;
        }
        $areas = Area::whereIn('id', $area_ids)->get();

        $areasArray= [];
        foreach ($areas as $area) {
            $array = $area->toArray();
            $array['types'] = $area->types->pluck('type')->toArray();
            $areasArray[] = $array;
        }

        $areas = collect($areasArray);
        return $areas;
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
        $area = Auth::user()->markedareas()->create($data);
        $area->makeArea();
        $area->addTypes($request->types);
        //long route to do this
        $array = $area->toArray();

        $area = collect($array);

        return $area;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $area = Area::findOrFail($id);
        //long route to do this
        $array = $area->toArray();
        $array['types'] = $area->types->pluck('type')->toArray();
        $area = collect($array);
        return $area;
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
        $area = Area::findOrFail($id);
        //update request
        $area->update($request->all());
        //delete types
        $area->types()->delete();
        //add new types
        $area->addTypes($request->types);

        $array = $area->toArray();

        $area = collect($array);
        return $area;
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
        $area = Area::findOrFail($id);
        //delete
        $area->types()->delete();
        $area->delete();
        //delete types

        return response()->json("{}", 200);

    }
}
