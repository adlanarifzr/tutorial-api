<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
    	$users = User::select('id', 'name')->get();

    	return response()->json([
    		'success' => true,
    		'message' => 'Success',
    		'data' => $users,
    	]);
    }

    public function insert(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|string|min:8|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
	    		'success' => false,
	    		'message' => 'Validation error.',
	    		'data' => $validator->errors(),
	    	]);
        }

    	$request->merge(['password' => bcrypt($request->password)]);
    	$user = User::create($request->all());

    	return response()->json([
    		'success' => true,
    		'message' => 'Success',
    		'data' => $user,
    	]);
    }

    public function view(Request $request)
    {
    	$user = User::find($request->id);

    	if(!$user) {
    		return response()->json([
	    		'success' => false,
	    		'message' => 'No user found.',
	    		'data' => null,
	    	]);
    	}

    	return response()->json([
    		'success' => true,
    		'message' => 'Success',
    		'data' => $user,
    	]);
    }

    public function update(Request $request)
    {
    	$user = User::find($request->id);

    	if(!$user) {
    		return response()->json([
	    		'success' => false,
	    		'message' => 'No user found.',
	    		'data' => null,
	    	]);
    	}

    	$validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
	    		'success' => false,
	    		'message' => 'Validation error.',
	    		'data' => $validator->errors(),
	    	]);
        }

    	if($request->password) {
    		$request->merge(['password' => bcrypt($request->password)]);
    	}

    	$user->update($request->all());

    	return response()->json([
    		'success' => true,
    		'message' => 'Success',
    		'data' => $user,
    	]);
    }

    public function delete(Request $request)
    {
    	$user = User::find($request->id);

    	if(!$user) {
    		return response()->json([
	    		'success' => false,
	    		'message' => 'No user found.',
	    		'data' => null,
	    	]);
    	}

    	$user->delete();

    	return response()->json([
    		'success' => true,
    		'message' => 'Success',
    		'data' => $user,
    	]);
    }
}
