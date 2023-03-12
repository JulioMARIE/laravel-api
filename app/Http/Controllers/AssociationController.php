<?php

namespace App\Http\Controllers;

use App\Model\Association;
use App\User;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $associations = $user->associations;
        return response()->json(['data' => $associations], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'denomination' => 'required'
        ]);
        
        $association = new Association($request->all());
        $user->associations()->save($association);
        return response()->json(['data' => $association], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Association  $association
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Association $association)
    {
        if ($user->id != $association->user_id) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        return response()->json(['data' => $association], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Association  $association
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Association $association)
    {
        if ($user->id != $association->user_id) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $this->validate($request, [
            'denomination' => 'required'
        ]);
        $association->fill($request->all());
        if ($association->isClean()) {
            return response()->json(['error' => 'Entrez une autre dénomination'], 404);
        }
        $association->save();
        return response()->json(['data' => $association], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Association  $association
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Association $association)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $association->delete();
        return response()->json(null, 204);
        //return response()->json(['data' => "Association: ".$association->denomination." ID: ".$association->id." supprimé"], 200); 
    }
}
