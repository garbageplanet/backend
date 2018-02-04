<?php
namespace App\Http\Controllers\Areas;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\User;
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

        $areasArray= [];

        foreach ($areas as $area) {
            $array = $area->toArray();
            $areasArray[] = $array;
        }

        $areas = collect($areasArray);

        return $areas;
        // return response()->json($areasArray, 200)->header('Access-Control-Allow-Origin', '*');

    }

    /**
     * Display a listing of the resource inside given bbox.
     *
     * @return Response
     */
    public function withinBounds(Request $request)
    {
        // parse bounds
        $bounds = str_replace(",", ", ", $request->bounds);

        $query = "SELECT * FROM areas WHERE areas.geom && ST_MakeEnvelope($bounds)";

        $areas = DB::select($query);

        //get id's of the areas
        $area_ids = [];

        foreach ($areas as $area) {
            $area_ids[] = $area->id;
        }

        $areas = Area::whereIn('id', $area_ids)->get();

        $areasArray= [];

        foreach ($areas as $area) {
            $array = $area->toArray();
            $areasArray[] = $array;
        }

        $areas = collect($areasArray);

        return $areas;
    }

      /**
     * Return a listing of features inside given area polygon.
     *
     * @return Response
     */
    public function indexWithinBounds($id)
    {
        $area = Area::findOrFail($id);

        $data = $area->trashesInsideArea($area);

        return $data;
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

        if (!Auth::check()) {
            $glome = Glome::createGlomeAccount();
            $user = User::create(['email' => $glome, 'password' => '12345678', 'name' => $glome]);
            Auth::attempt(['email' => $glome, 'password' => '12345678']);
        }

        /*
         * String format for latlngs
         * [60.18857, 25.13337],[60.18776, 25.13267],[..., ...], ...
         *
        */

        $validator = $this->validate($request, [
              'contact'     => 'email|nullable'
            , 'game'        => 'boolean|nullabled'
            , 'max_players' => 'numeric|max:100|nullable'
            , 'title'       => 'required|unique:areas|min:12|max:255'
            , 'latlngs'     => array(
                  'required',
                  'regex:/^(\[([-+]?\d{1,2}[.]\d+)\s*,\s*([-+]?\d{1,3}[.]\d+)\]\s*,?)+$/u'
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
        ]);

        $area = Auth::user()->createdAreas()->create($data);
        // Create the postgis geometry
        $area->makeArea();
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
        $area->delete();

        return response()->json("{}", 200);

    }
}
