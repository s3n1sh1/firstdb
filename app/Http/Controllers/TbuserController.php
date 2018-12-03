<?php

namespace App\Http\Controllers;

use App\Tbuser;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TbuserController extends BaseController
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('tuuser', 'password');

        // dd(JWTAuth::attempt($credentials));

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(['token' => "Bearer $token"])->header('Authorization', $token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tuname' => 'required|string|max:100',
            'tuuser' => 'required|string|max:50|unique:tbuser',
            'tupass' => 'required|string|min:6|max:100|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = Tbuser::create([
            'tuname' => $request->get('tuname'),
            'tuuser' => $request->get('tuuser'),
            'tupass' => bcrypt($request->get('tupass')),
            // 'tupass' => Hash::make($request->get('tupass')),
            'turgid' => $request->get('tuname'),
            'turgdt' => Date("Y-m-d H:i:s"),
            'tuchid' => $request->get('tuname'),
            'tuchdt' => Date("Y-m-d H:i:s"),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), 201);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    public function refresh()
    {
        return response(['status' => 'success']);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function loadUser(Request $request)
    {
        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $pagination = Tbuser::where('tuuserid', '<>', '1')->paginate($perPage);
        $pagination->appends([
            'sort' => request()->sort,
            'filter' => request()->filter,
            'per_page' => request()->per_page
        ]);

        return response()->json($pagination);
    }

    public function saveUser(Request $request)
    {
        $receive = $request->all();
        $tbuser = get_object_vars($receive['user']);
        $mode = $receive['mode'];
        $currentuser = JWTAuth::user();

        if($mode == '1') {
            $validator = Validator::make($tbuser, [
                'tuname' => 'required|string|max:100',
                'tuuser' => 'required|string|max:50|unique:tbuser',
                'tupass' => 'required|string|min:8|max:100|confirmed',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 422);
            }
        }

        switch ($mode) {
            case "1":
                $tbuser['tupass'] = bcrypt($tbuser['tupass']);
                Tbuser::insert(
                    $this->fnFieldSyntax (
                        $tbuser, $currentuser->tuuser, '1', 
                        ['tuuser','tuname','tupass','tuiran'] 
                    )
                );
                $message = "User Created";
                break;
            case "2":
                Tbuser::where('tuuserid', $tbuser['tuuserid'])->update(
                    $this->fnFieldSyntax (
                        $tbuser, $currentuser->tuuser, '2',  
                        ['tuname','tuiran']
                    )
                );
                $message = "User Updated";
                break;
            case "3":
                Tbuser::where('tuuserid', $tbuser['tuuserid'])->delete();
                $message = "User Deleted";
                break;
        }

        return ["message"=>$message];
    }
}
