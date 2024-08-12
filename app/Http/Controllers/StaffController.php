<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ResetCodePassword;
use App\Mail\SendCodeStaff;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginStaff']]);
    }


    public function showAllStaff()
    {
        $employees = User::where('role', '!=', 'manager')->get();
        return response()->json($employees, 200);
    }


    public function createStaff(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'role' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'number' => 'required|string|min:10',
            'password' => 'required|string|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  ]);


       if($validator->fails())  {
        return response()->json($validator->errors()->toJson(), 400);
              }

        $imageName = null;
        if ($request->hasFile('image')) {
            $photo = $request->image;
            $imageName = 'imag' . time() . '.' . $photo->getClientOriginalExtension();
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
                  ->send(new SendCodeStaff($codeData->code));

        return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
        ], 201);
         }


    public function showStaff($id)
    {
        $staff = User::where('id',$id)->get();
        return response()->json($staff, 200);
        }

    public function updateStaff(Request $request, $id)
    {
        $staff = User::where('id',$id)->get();

        $request->validate([
            'name' => 'required|string|between:2,100',
            'role' => 'required|string',
            // 'email' => 'required|string|email|max:100|unique:users' .$id,
            'number' => 'required|string|min:10',
            // 'password' => 'required|string|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $photo = $request->image;
            $imageName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads'), $imageName);
                }


            $staff= User::find($id)->update([
            'name' => $request->name,
            'role' => $request->role,
            // 'email' => $request->email,
            'number' =>  $request->number,
            // 'password' => $request->password ? bcrypt($request->password) : $staff->password,
            'imagePath'=> $imageName ? ' /uploads/ ' . $imageName : null,
        ]);

        return response()->json(['message' => 'successfully updateStaff',
                                    'user' =>  $staff], 200);
    }



    public function searchStaff($request)
    {
        $query = $request;

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required.'], 400);
        }

        $columns = [
            'name',
            'email',
            'role',
            'number',
        ];


        $staff = User::where(function($q) use ($columns, $query) {
            foreach ($columns as $column) {
                $q->orWhereRaw("LOWER($column) LIKE ?", ['%' . strtolower($query) . '%']);
            }
        })->where('role','!=','manager')->get();


        if ($staff->isEmpty()) {
            return response()->json(['message' => 'No staff found with the provided query.'], 404);
        }

        return response()->json($staff);
    }

    public function destroyStaff($id)
    {
        $staff=User::firstWhere('id',$id);
        ResetCodePassword::where('email', $staff->email)->delete();
        $staff->delete();
        return response()->json(['message' => 'successfully destroyStaff'],200);
    }



    public function loginStaff(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
           'fcm_token' => 'required|string',
        ]);

        $validatorArray = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        if (! $token = auth()->attempt($validatorArray)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $User = auth()->user();
        $User->fcm_token = $request->fcm_token;
        $User->save();

        return response()->json([
            "user" => auth()->user(),
            "_token" => $token,
            ]);

    }


    }
