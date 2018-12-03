<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use DB;
use Illuminate\Support\Facades\Storage as Storage;

class BaseController extends Controller
{
    function fnFieldSyntax ($request, $username, $mode, $fields) {

        $finalfield = array_filter( $request,
                            function ($key) use ($fields) {
                                return in_array($key, $fields);
                            },
                            ARRAY_FILTER_USE_KEY
                        );

        $prefix = SubStr($fields[0],0,2);

        switch ($mode) {
            case "1":
                $finalfield = array_merge($finalfield, array(
                                            $prefix."dlfg"=>"0",
                                            $prefix."rgid"=>$username,
                                            $prefix."rgdt"=>Date("Y-m-d H:i:s"),
                                            $prefix."chid"=>$username,
                                            $prefix."chdt"=>Date("Y-m-d H:i:s"),
                                            $prefix."chno"=>"0"
                                        ));
                break;
            case "2": 
                $finalfield = array_merge($finalfield, array(
                                            $prefix."dlfg"=>"0",
                                            $prefix."chid"=>$username,
                                            $prefix."chdt"=>Date("Y-m-d H:i:s"),
                                            $prefix."chno"=>DB::raw($prefix."chno + 1")
                                        ));           
                break;
            case "3":
                $finalfield = array_merge($finalfield, array(
                                            $prefix."dlfg"=>"1",
                                            $prefix."chid"=>$username,
                                            $prefix."chdt"=>Date("Y-m-d H:i:s"),
                                            $prefix."chno"=>DB::raw($prefix."chno + 1") ,
                                            $prefix."csid"=>$username,
                                            $prefix."csdt"=>Date("Y-m-d H:i:s")
                                        ));           
                break;
            default:
                $finalfield = array_merge($finalfield, array(
                                            $prefix."chid"=>$username,
                                            $prefix."chdt"=>Date("Y-m-d H:i:s")
                                        ));                       
                break;
        }

        return $finalfield;
    }
}
