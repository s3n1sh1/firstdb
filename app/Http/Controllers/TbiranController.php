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

    public function loadRecord(Request $request)
    {
        $year = Carbon::parse($request->date)->format('Y');
        
        $currentuser = JWTAuth::user();

        $dropTempTables = DB::unprepared(
            DB::raw("
                DROP TABLE IF EXISTS temp_month;
            ")
        );

        $createTempTables = DB::unprepared(
            DB::raw("
                CREATE TEMPORARY TABLE temp_month 
                AS (
                    select * from (
                        select '".$currentuser->tuuserid."' as id, '".$year."01' as mnth union
                        select '".$currentuser->tuuserid."', '".$year."02' union
                        select '".$currentuser->tuuserid."', '".$year."03' union
                        select '".$currentuser->tuuserid."', '".$year."04' union
                        select '".$currentuser->tuuserid."', '".$year."05' union
                        select '".$currentuser->tuuserid."', '".$year."06' union
                        select '".$currentuser->tuuserid."', '".$year."07' union
                        select '".$currentuser->tuuserid."', '".$year."08' union
                        select '".$currentuser->tuuserid."', '".$year."09' union
                        select '".$currentuser->tuuserid."', '".$year."10' union
                        select '".$currentuser->tuuserid."', '".$year."11' union
                        select '".$currentuser->tuuserid."', '".$year."12'
                    ) temp_month
                );
            ")
        );

        if($createTempTables){
            $yearquery = DB::table('temp_month');
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $pagination = Tbiran::rightJoinSub($yearquery, 'temp_month', function($join){
                                $join->on('mnth','=','timont');
                                $join->where('id','=','tiuserid');
                            })
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
