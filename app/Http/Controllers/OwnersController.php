<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Car;
Use App\Owner;
use Session;
use Validator;

class OwnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners =  Owner::all();

        return view("owners.index", [
            "owners"=>$owners
            ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $owners= Owner::all();
       return view ("owners.create", [ "owners"=> $owners,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
                'surname.required' => 'Neįrašyta Pavardė',
                'name.required' => 'Neįrašytas Vardas',
                'phone.required' => 'Telefonas turi buti įvestas',
                'required' => 'Laukelis :attribute turi buti įvestas'
            ];
        // Patikriname uzklausos duomenis
		$validatedOwner = $request->validate([
    
                // 1. Formos laukelio padinimas 
                // 2. visos taisykles
    			// Jei naudojame unique po dvitaskio duomenu bazes pavadinimas
    			// kurioje reiksme turi buti unikali
                'name' => 'required',
                'surname' => 'required',
    			'cars_id' => 'required',
    			'phone' => 'required',
    		], $messages);
    
        $owners= new Owner;

        $owners->name = $request ->name;
        $owners->surname = $request ->surname;
        $owners->cars_id = $request ->cars_id;
        $owners->phone = $request ->phone;
        
        $owners->save();

        Session::flash( 'status', 'Sukurtas naujas savininkas' );
        return redirect()->route("owners.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $owners= Owner::find($id);
        return view ("owners.edit", [ "owners"=> $owners,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
    		'required' => 'Laukelis :attribute turi buti užpildytas'
            ];
        Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'cars_id' => 'required',
            'phone' => 'required|min:9',
    ], $messages)->validate();


        $owners = Owner::find($id);
        
        $owners->name = $request ->name;
        $owners->surname = $request ->surname;
        $owners->cars_id = $request ->cars_id;
        $owners->phone = $request ->phone;
        
        $owners->save();
       
        
        Session::flash( 'status', 'Redagavimas baigtas' );
        return redirect()->route("owners.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $owners = Owner::find($id);
        $owners->delete();

        // Susikuriu sesijos pranesima
		Session::flash( 'status', 'Savininkas  ištrintas ' );
        return redirect()->route("owners.index");

    }
}
