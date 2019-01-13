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

        $query = Tbuser::select('tuuserid as tiuserid','tuuser','tuname','tuiran as tiiran')
                            ->addSelect(DB::raw("'' as timont"))
                            ->whereNotIn('tuuserid', [1, 2, 3, 4, 5])
                            ->where('tumont', '<=', $month)
                            ->whereNotIn('tuuserid', Tbiran::where('timont', '=', $month)->pluck('tiuserid'));
        
        $pagination = $query->paginate($query->count());

        return response()->json($pagination);
    }

    public function loadSettle(Request $request)
    {
        $month = Carbon::parse($request->date)->format('Ym');

        $query = Tbiran::select('tiiranid','tuuser','tuname','tiiran')
                            ->leftJoin('tbuser', 'tuuserid', '=', 'tiuserid')
                            ->whereNotIn('tuuserid', [1, 2, 3, 4, 5])
                            ->where('timont', '=', $month);

        $pagination = $query->paginate($query->count());

        return response()->json($pagination);
    }

    public function loadRecord(Request $request)
    {
        // DB::enableQueryLog();

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
                        select '".$currentuser->tuuserid."' as id
                            , '".$year."01' as mnth
                            , monthname('".$year."0101') as bln union
                        select '".$currentuser->tuuserid."', '".$year."02', monthname('".$year."0201') union
                        select '".$currentuser->tuuserid."', '".$year."03', monthname('".$year."0301') union
                        select '".$currentuser->tuuserid."', '".$year."04', monthname('".$year."0401') union
                        select '".$currentuser->tuuserid."', '".$year."05', monthname('".$year."0501') union
                        select '".$currentuser->tuuserid."', '".$year."06', monthname('".$year."0601') union
                        select '".$currentuser->tuuserid."', '".$year."07', monthname('".$year."0701') union
                        select '".$currentuser->tuuserid."', '".$year."08', monthname('".$year."0801') union
                        select '".$currentuser->tuuserid."', '".$year."09', monthname('".$year."0901') union
                        select '".$currentuser->tuuserid."', '".$year."10', monthname('".$year."1001') union
                        select '".$currentuser->tuuserid."', '".$year."11', monthname('".$year."1101') union
                        select '".$currentuser->tuuserid."', '".$year."12', monthname('".$year."1201')
                    ) temp_month
                );
            ")
        );

        if($createTempTables){
            $yearquery = DB::table('temp_month');
        }

        $query = Tbiran::select('tiiranid','bln',DB::raw('(case when tiiranid is null then tuiran else tiiran end) As iuran'))
                            ->rightJoinSub($yearquery, 'temp_month', function($join){
                                $join->on('mnth','=','timont');
                                $join->on('id','=','tiuserid');
                            })
                            ->leftJoin('tbuser', 'tuuserid', '=', 'id')
                            ->whereRaw('mnth >= tumont')
                            ->whereRaw('left(mnth,4) <= date_format(now(),"%Y")');
                            // ->whereRaw('mnth <= date_format(now(),"%Y%m")');
        
        $pagination = $query->paginate($query->count());

        // var_dump(DB::getQueryLog());

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
            case "3":
                Tbiran::where('tiiranid', $receive['iuran'])->delete();
                $message = "Iuran Removed";
                break;
        }

        return ["message"=>$message];
    }
}
