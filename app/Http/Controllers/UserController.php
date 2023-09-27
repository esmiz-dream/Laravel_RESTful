<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        if ($user) {
            return new UserCollection($user);
        }
        return response()->json(['error', 'No user are there!!'], 200);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function validation_rules()
    {
        $rules = array(
            "name" => "required|min:5",
            "password" => "required|min:4",
            "email" => "required|email",
        );
        return $rules;

    }
    public function store(Request $request)
    {

        $rules = self::validation_rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $admin = new User();
            $admin->name = $request->name;
            $admin->password = Hash::make($request->password);
            $admin->email = $request->email;
            $result = $admin->save();
            if ($result) {
                return response()->json(['success', 'User Created Successfully!!'], 201);
            } else {
                return response()->json(['error', 'Faild to create New User!!'], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user) {
            return new UserResource($user);
        }
        return response()->json(['error', 'no user in the specified id!!'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $rules = self::validation_rules();
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        } else {
            $user = User::find($id);
            if ($user) {
                $user->name = $req->name;
                $user->email = $req->email;
                $user->password = Hash::make($req->password);
                $result = $user->save();
                if ($result) {
                    return response()->json(['success', 'User Updated Successfully!!'], 201);
                } else {
                    return response()->json(['error', 'Faild to update User!!'], 200);
                }
            } else {
                return response()->json(['error', "user not Found"], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $result = $user->delete();
            if ($result) {
                return response()->json(['success',   "Admin account deleted Successfully!!"], 201);
              
            } else {
                return response()->json(['error',  "Faild to delete The Admin!!"], 200);
               
            }
        } else {
            return response()->json(['error',  "user not found"], 200);
            
        }
    }
}