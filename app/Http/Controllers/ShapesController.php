
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\shape;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class ShapesController extends Controller
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
        $shapes = Shape::all();

        //long route to do this
        //dd($shapes);
        $shapesArray= [];
        foreach ($shapes as $shape) {
            $array = $shape->toArray();
            $array['types'] = $shape->types->pluck('type')->toArray();
            $shapesArray[] = $array;
        }

        $shapes = collect($shapesArray);
        return $shapes;
        //return response()->json($shapesArray, 200)->header('Access-Control-Allow-Origin', '*');

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

        $shapes = DB::select('
            SELECT *
            FROM shapes

            WHERE shapes.geom && ST_MakeEnvelope(?, ?, ?, ?)'
            ,
            [$sw_lat, $sw_lng, $ne_lat, $ne_lng]);

        //get id's of the shapes
        $shape_ids = [];
        foreach ($shapes as $shape) {
            $shape_ids[] = $shape->id;
        }
        $shapes = shape::whereIn('id', $shape_ids)->get();

        $shapesArray= [];
        foreach ($shapes as $shape) {
            $array = $shape->toArray();
            $array['types'] = $shape->types->pluck('type')->toArray();
            $shapesArray[] = $array;
        }

        $shapes = collect($shapesArray);
        return $shapes;
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
        $shape = Auth::user()->markedshapes()->create($data);
        $shape->makePoint(); // FIXME array of points
        $shape->addTypes($request->types);
        //long route to do this
        $array = $shape->toArray();
        $array['types'] = $shape->types->pluck('type')->toArray(); // FIXME we need type to be reserved to the type of shape, not the type of trash

        $shape = collect($array);

        return $shape;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $shape = Shape::findOrFail($id);
        //long route to do this
        $array = $shape->toArray();
        $array['types'] = $shape->types->pluck('type')->toArray();
        $shape = collect($array);
        return $shape;
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
        $shape = Shape::findOrFail($id);

        //update request
        $shape->update($request->all());
        //delete types
        $shape->types()->delete();
        //add new types
        $shape->addTypes($request->types);

        $array = $shape->toArray();
        $array['types'] = $shape->types->pluck('type')->toArray();

        $shape = collect($array);
        return $shape;
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
        $shape = Shape::findOrFail($id);
        //delete
        $shape->types()->delete();
        $shape->delete();
        //delete types

        return response()->json("{}", 200);

    }
}
