<?php

namespace App\Http\Controllers;

use App\Tbiran;
use App\Tbuser;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use JWTAuth;
use Carbon\Carbon;
use DB;

class TbiranController extends BaseController
{
    public function loadIuran(Request $request)
    {
        $month = Carbon::parse($request->date)->format('Ym');

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $pagination = Tbuser::select('tuuserid as tiuserid','tuuser','tuname','tuiran as tiiran')
                            ->addSelect(DB::raw("'' as timont"))
                            ->where('tuuserid', '<>', '1')
                            ->where('tumont', '<=', $month)
                            ->whereNotIn('tuuserid', Tbiran::where('timont', '=', $month)->pluck('tiuserid'))
                            ->paginate($perPage);
        
        $pagination->appends([
            'sort' => request()->sort,
            'filter' => request()->filter,
            'per_page' => request()->per_page
        ]);

        return response()->json($pagination);
    }

    public function saveIuran(Request $request)
    {
        $receive = $request->all();
        $mode = $receive['mode'];
        $month = $receive['month'];
        $currentuser = JWTAuth::user();
        
        switch ($mode) {    
            case "1":
                foreach($receive['iuran'] as $index) {
                    $tbiran = get_object_vars($index);
                    $tbiran['timont'] = Carbon::parse($month)->format('Ym');
                    Tbiran::insert(
                        $this->fnFieldSyntax (
                            $tbiran, $currentuser->tuuser, '1', 
                            ['tiuserid','tiiran','timont']
                        )
                    );
                }
                $message = "Iuran Settled";
                break;
        }

        return ["message"=>$message];
    }
}
