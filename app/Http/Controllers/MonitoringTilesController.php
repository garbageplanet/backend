<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use App\MonitoringTile;

class MonitoringTilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['listByUser', 'store', 'destroy']]);
    }

    public function listByUser()
    {
        return Auth::user()->monitoringTiles;
    }

    public function store(Request $request)
    {
        $monitoringTile = Auth::user()->monitoringTiles()->create($request->all());
        return $monitoringTile;
    }

    public function destroy($id)
    {
        $monitoringTile = MonitoringTile::findOrFail($id);
        $monitoringTile->delete();
        return response()->json("{}", 200);
    }

}
