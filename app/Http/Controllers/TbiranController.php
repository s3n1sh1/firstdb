<?php

namespace App\Http\Controllers;

use App\Tbiran;
use App\Tbuser;
use Illuminate\Http\Request;

class TbiranController extends Controller
{
    public function loadIuran(Request $request)
    {
        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $pagination = Tbuser::where('tuuserid', '<>', '1')
                            ->whereNotIn('tuuserid', Tbiran::where('timont', '=', '201812')->pluck('tiuserid'))
                            ->paginate($perPage);
        
        $pagination->appends([
            'sort' => request()->sort,
            'filter' => request()->filter,
            'per_page' => request()->per_page
        ]);

        return response()->json($pagination);
    }
}
