<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('isAdmin', 0)->get();
        return response()->json(['data' => $users], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view to create in web app
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return response()->json(['data'=> $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user->isAdmin) return response()->json(['error'=>'You can\'t display admin user' ]);
        return response()->json(['data' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return view to update in web app
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($user->isAdmin) return response()->json(['error'=>'You can\'t update admin user' ]);

        /* $rules = [
            'email' => 'email|unique:users,email,'.$user->$id,
            'password' => 'min:6|confirmed',
            'isAdmin' => 'in:true,false',
        ]; */

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email') && $request->email!= $user->email) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        if ($request->has('isAdmin')) {
            $user->isAdmin = $request->isAdmin;
        }

        $user->save();

        return response()->json(['data'=>$user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin) return response()->json(['error'=>'You can\'t delete admin user' ]);
        $user->delete();
        return response()->json(null, 204);
    }
}
