<?php

namespace App\Http\Controllers;

use App\Tbnews;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use JWTAuth;
use Carbon\Carbon;
use DB;

class TbnewsController extends BaseController
{
    public function loadNews(Request $request)
    {
        $pagination = Tbnews::paginate(Tbnews::count());

        return response()->json($pagination);
    }

    public function saveNews(Request $request)
    {
        $receive = $request->all();
        $tbnews = get_object_vars($receive['news']);
        $mode = $receive['mode'];
        $currentuser = JWTAuth::user();

        switch ($mode) {
            case "1":
                Tbnews::insert(
                    $this->fnFieldSyntax (
                        $tbnews, $currentuser->tuuser, '1', 
                        ['tnname','tndesc']
                    )
                );
                $message = "News Created";
                break;
            case "2":
                Tbnews::where('tnnewsid', $tbnews['tnnewsid'])->update(
                    $this->fnFieldSyntax (
                        $tbnews, $currentuser->tuuser, '2',  
                        ['tndesc']
                    )
                );
                $message = "News Updated";
                break;
            case "3":
                Tbnews::where('tnnewsid', $tbnews['tnnewsid'])->delete();
                $message = "News Deleted";
                break;
        }

        return ["message"=>$message];
    }
}
