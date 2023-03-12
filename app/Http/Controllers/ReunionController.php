<?php

namespace App\Http\Controllers;

use App\Model\Reunion;
use Illuminate\Http\Request;

use App\Model\Membre;
use App\User;
use App\Model\Association;
use App\Model\MembreReunion;

class ReunionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Association $association)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        /* $reunions = $association->membres()
                                ->whereHas('reunions')
                                ->with('reunions')
                                ->get()
                                ->pluck('reunions')
                                ->collapse()
                                ->unique('id')
                                ->values(); */
        $reunions = $association->reunions();
        return response()->json(['nbr'=> sizeof($reunions), 'data'=> $reunions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Association $association, Membre $membre)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $this->validate($request, [
            'datereunion' => 'required',
            /* 'heure' => 'required',
            'lieu' => 'required',
            'libelle' => 'required', */
            'mtcot'=> 'required',
            "mtcotmembre-$membre->id" => 'required',
            "statutpresence-$membre->id" => 'in:'.MembreReunion::STATUTPRESENCE_PRES.','.MembreReunion::STATUTPRESENCE_ABS.','.MembreReunion::STATUTPRESENCE_PERMI,
        ]);

        $newreunion = new Reunion();
        $newreunion->datereunion = $request->datereunion;
        /* $newreunion->heure = $request->heure;
        $newreunion->lieu = $request->lieu;
        $newreunion->libelle = $request->libelle; */
        $newreunion->mtcot = $request->mtcot;
        $reunion = $association->reunions()->save($newreunion);

        $membre_reunion = [$membre->id => ['statutpresence' => $request["statutpresence-$membre->id"],
                    'mtcot' => $request["mtcotmembre-$membre->id"],
                  ],
                ];
        $reunion->membres()->attach($membre_reunion);
        return response()->json(['data' => 'ok'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Association $association, Reunion $reunion)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $membres = $reunion->membres;
        return response()->json(['date'=>$reunion->datereunion,'mtcot'=>$reunion->mtcot,'nbr'=> sizeof($membres), 'data'=> $membres]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Association $association, Reunion $reunion)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }

        if ($request->has('datereunion')) {
            $reunion->datereunion = $request->datereunion;
        }
        if ($request->has('mtcot')) {
            $reunion->mtcot = $request->mtcot;
        }
        if ($request->has('heure')) {
            $reunion->heure = $request->heure;
        }
        if ($request->has('lieu')) {
            $reunion->lieu = $request->lieu;
        }
        if ($request->has('libelle')) {
            $reunion->libelle = $request->libelle;
        }
        
        $reunion->save();

        return response()->json(['data' => $reunion], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Association $association, Reunion $reunion)
    {
        /* if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $reunion->delete();
        return response()->json(null, 204); */
    }
}
