<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\ResetCodePassword;
use App\Mail\SendCode;
use Illuminate\Support\Facades\Mail;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            "user" => auth()->user(),
            "_token" => $token,
            ]);

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */



    public function register(Request $request) {

         $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'type' => 'required|string',
                'email' => 'required|string|email|max:100|unique:users',
                'number' => 'required|string|min:10',
                'password' => 'required|string|min:6',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);

        if($validator->fails())  {
            return response()->json($validator->errors()->toJson(), 400);
            }

            $imageName = null;
            if ($request->hasFile('image')) {
                $photo = $request->image;
                $imageName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads'), $imageName);
            }


        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'imagePath'=> $imageName ? ' /uploads/ ' . $imageName : null,
                        'password' => bcrypt($request->password),]
                )
            );


        $code = mt_rand(100000, 999999);
        $email['email']=$request->email;

        $codeData = ResetCodePassword::create([
                                    'code'=>$code,
                                    'email'=>$request->email
                                 ]);

         // Send email to user
         Mail::to($request->email)
              ->send(new SendCode($codeData->code));

        return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
        ], 201);
    }




    // public function register(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'password' => 'required|string|confirmed|min:6',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $user = User::create(array_merge(
    //                 $validator->validated(),
    //                 ['password' => bcrypt($request->password)]
    //             ));

    //     return response()->json([
    //         'message' => 'User successfully registered',
    //         'user' => $user
    //     ], 201);
    // }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {

        $user=User::where('id',Auth::id())->get();
        return response()->json($user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
