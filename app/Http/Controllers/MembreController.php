<?php

namespace App\Http\Controllers;

use App\Model\Membre;
use App\User;
use App\Model\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /* public function __construct()
    {
        $this->middleware('auth');
    } */

    
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
        /* $mttotalapayer_asso = $association->membres()
                                            ->whereHas('reunions')
                                            ->with('reunions')
                                            ->get()
                                            ->pluck('reunions')
                                            ->collapse()
                                            ->unique('id')
                                            ->sum('mtcot'); */
       /*  $sql_mttotalapayer = "SELECT COUNT(R.id) AS totalNBR, SUM(R.mtcot) AS totalCOT FROM (SELECT DISTINCT reunions.id FROM `associations`,`membres`,`reunions`,`membre_reunion` WHERE associations.id=membres.association_id AND membre_reunion.membre_id=membres.id AND membre_reunion.reunion_id=reunions.id AND associations.id=".$association->id.") as R_A, (SELECT * FROM reunions) as R WHERE R.id=R_A.id";
        $mttotalapayer_asso = DB::SELECT($sql_mttotalapayer) */;
        //pas besoin $membres = $association->membres;
        $sql = "SELECT SOMMECOTMEMBRES.*, MontantTotalAPayer, NBRREUNIONASSO FROM (SELECT membres.*, SUM(membre_reunion.mtcot) AS SOMMECOTMEMBRE, associations.id as assoId FROM `membres`,`associations`,`reunions`, `membre_reunion` WHERE associations.id=".$association->id." AND membres.association_id=associations.id AND reunions.association_id=associations.id AND membre_reunion.membre_id=membres.id AND membre_reunion.reunion_id=reunions.id GROUP BY membres.id) AS SOMMECOTMEMBRES, (SELECT COUNT(reunions.id) AS NBRREUNIONASSO, SUM(reunions.mtcot) AS MontantTotalAPayer, associations.id as assoId  FROM `reunions`,`associations` WHERE associations.id=".$association->id." AND reunions.association_id=associations.id) AS TOTALCOTREUNIONASSO WHERE SOMMECOTMEMBRES.assoId = TOTALCOTREUNIONASSO.assoId";
        //$sql = "SELECT membres.*, SUM(membre_reunion.mtcot) AS SOMMECOTMEMBRE, SUM(membre_reunion.present) AS PRESENCEMEMBRE, SUM(membre_reunion.perm) AS PERMISSIONMEMBRE FROM `membres`,`associations`,`reunions`,`membre_reunion` WHERE associations.id=".$association->id." AND membres.association_id=associations.id AND membre_reunion.reunion_id=reunions.id AND membre_reunion.membre_id=membres.id GROUP BY membres.id";
        $membres = DB::SELECT($sql);
        return response()->json(['membres' => $membres], 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Association $association)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $this->validate($request, [
            'nom' => 'required',
            'prenom' => 'required',
            'addresse' => 'required',
            'contact' => 'required'
        ]);

        $membre = new Membre($request->all());
        $association->membres()->save($membre);
        return response()->json(['data' => $membre], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Membre  $membre
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Association $association, Membre $membre)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        if ($association->id != $membre->association_id) {
            return response()->json(['error' => "Le membre n'est pas dans cette association ID: ".$association->id], 404); 
        }
        $totalpresmembre = $membre->reunions()->where('membre_reunion.statutpresence', '=', 'présent')->count();
        $totalpermmembre = $membre->reunions()->where('membre_reunion.statutpresence', '=', 'permissionnaire')->count();
        $totalabsmembre = $membre->reunions()->where('membre_reunion.statutpresence', '=', 'absent')->count();
        $lastreunionStatut = $membre->reunions()->orderBy('datereunion', 'desc')->pluck('membre_reunion.statutpresence')->first();
        $sumcotmembreapayer = $membre->reunions()->sum('reunions.mtcot');
        $sumnbrreunions = $membre->reunions()->count('reunions.id');
        $sumcotmembre = $membre->reunions()->sum('membre_reunion.mtcot');
        /* $sql_pres = "SELECT COUNT(membre_reunion.id) AS totalpresmembre FROM `membre_reunion` WHERE statutpresence=\"présent\" AND membre_id=".$membre->id;
        $totalpresmembre = DB::SELECT($sql_pres);
        $sql_perm = "SELECT COUNT(membre_reunion.id) AS totalpermmembre FROM `membre_reunion` WHERE statutpresence=\"permissionnaire\" AND membre_id=".$membre->id;
        $totalpermmembre = DB::SELECT($sql_perm);
        $sql_abs = "SELECT COUNT(membre_reunion.id) AS totalabsmembre FROM `membre_reunion` WHERE statutpresence=\"absent\" AND membre_id=".$membre->id;
        $totalabsmembre = DB::SELECT($sql_abs); */
        return response()->json(['sumcotmembreapayer'=> $sumcotmembreapayer, 'sumnbrreunions'=> $sumnbrreunions,'sumcotmembre'=>$sumcotmembre, 'totalpermmembre'=>$totalpermmembre,
        'totalpresmembre'=> $totalpresmembre, 'totalabsmembre'=> $totalabsmembre,'lastreunionStatut'=> $lastreunionStatut, 'details' => $membre->reunions], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Membre  $membre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Association $association, Membre $membre)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }

        if ($request->has('nom')) {
            $membre->nom = $request->nom;
        }
        if ($request->has('prenom')) {
            $membre->prenom = $request->prenom;
        }
        if ($request->has('addresse')) {
            $membre->addresse = $request->addresse;
        }
        if ($request->has('contact')) {
            $membre->contact = $request->contact;
        }
        if ($request->has('mdp')) {
            $membre->mdp = bcrypt($request->mdp);
        }
        if ($request->has('datenaissance')) {
            $membre->datenaissance = $request->datenaissance;
        }
        if ($request->has('photoprofil')) {
            $membre->photoprofil = $request->photoprofil;
        }
        if ($request->has('profession')) {
            $membre->profession = $request->profession;
        }
        if ($request->has('situationmatri')) {
            $membre->situationmatri = $request->situationmatri;
        }

        $membre->save();

        return response()->json(['data'=>$membre], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Membre  $membre
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Association $association, Membre $membre)
    {
        if ($user->id != $association->user_id && !($user->isAdmin)) {
            return response()->json(['error' => "L'association n'est pas pour cet utilisateur ID: ".$user->id], 404); 
        }
        $membre->delete();
        return response()->json(null, 204);
    }
}
