<?php

namespace App\Http\Controllers;

use App\Model\MembreReunion;
use Illuminate\Http\Request;
use App\Model\Reunion;

use App\Model\Membre;
use App\User;
use App\Model\Association;

class MembreReunionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\MembreReunion  $membreReunion
     * @return \Illuminate\Http\Response
     */
    public function show(MembreReunion $membreReunion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\MembreReunion  $membreReunion
     * @return \Illuminate\Http\Response
     */
    public function edit(MembreReunion $membreReunion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\MembreReunion  $membreReunion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Association $association, Membre $membre, Reunion $reunion)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $membre_reunion = $membre->reunions()->where([['membre_reunion.membre_id', '=', $membre->id], ['membre_reunion.reunion_id', '=', $reunion->id]]);
        $membre_reunion_statut = $membre_reunion->pluck('statutpresence')->first();
        $membre_reunion_mtcotmembre = $membre_reunion->pluck('membre_reunion.mtcot')->first();
        $this->validate($request, [
            "statutpresence-$membre->id" => 'in:'.MembreReunion::STATUTPRESENCE_PRES.','.MembreReunion::STATUTPRESENCE_ABS.','.MembreReunion::STATUTPRESENCE_PERMI,
        ]);
        if ($request->has("statutpresence-$membre->id")) {
            $membre_reunion_statut = $request["statutpresence-$membre->id"];
        }
        if ($request->has("mtcotmembre-$membre->id")) {
            $membre_reunion_mtcotmembre = $request["mtcotmembre-$membre->id"];
        }
        /* if ($membre->reunions()->get($reunion)->isClean()) {
            return response()->json(['error' => 'Entrez d\'autres informations'], 404);
        } */
        $membre_reunion_update = array('statutpresence' => $membre_reunion_statut,
                            'mtcot' => $membre_reunion_mtcotmembre);
        $data = $membre->reunions()->updateExistingPivot($reunion->id, $membre_reunion_update, false);
        return response()->json(['data' => $data], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\MembreReunion  $membreReunion
     * @return \Illuminate\Http\Response
     */
    public function destroy(MembreReunion $membreReunion)
    {
        //
    }
}
