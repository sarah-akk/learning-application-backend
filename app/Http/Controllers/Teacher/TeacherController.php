<?php

namespace App\Http\Controllers\Teacher;
use App\Models\AdminUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller {
    public function login(Request $request)
    {
        $validUser = Validator::make($request->all(),[
            'username' => ['required'],
            'password' => ['required']
        ]);

        $validated = $validUser->validated();
        $map =[];
        $map['username'] =$validated['username'];


        $user =AdminUser::where($map)->first();

        if(empty($user->id)){
            return response()->json([
                'message' => 'failed'
            ], 401);
        }

        if(!Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' => 'failed'
            ], 401);
        }

        $accessToken = $user->createToken(uniqid())->plainTextToken;
        $user->access_Token = $accessToken;

        if(!$validUser->fails()) {
            return response()->json([
                'message' => 'user log in successfully',
                 'user' => $user,
                'token' => $user['token'],
            ], 200);
        }

    }
}
