<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate request parameters
        $validated = $this->validate($request, [
            'name' => 'required|string|max:255',
            'short_name' => 'string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'string|max:255',
            'phone' => 'required|string|max:255',
            'fax' => 'string|max:255',
            'email' => 'required|string|max:255',
            'website' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'mission' => 'required|string|max:255',
            'vision' => 'required|string|max:255',
            'motto' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'accreditation' => 'required|string|max:255',
            'accreditation_date' => 'required|string|max:255',
            'accreditation_expiration' => 'required|string|max:255',
            'accreditation_authority' => 'required|string|max:255'
        ]);
        //store the model in the database
        $university = new University;
        $university->name = $request->name;
        $university->short_name = $request->short_name;
        $university->address = $request->address;
        $university->city = $request->city;
        $university->zip = $request->zip;
        $university->phone = $request->phone;
        $university->fax = $request->fax;
        $university->email = $request->email;
        $university->website = $request->website;
        $university->logo = $request->logo;
        $university->description = $request->description;
        $university->mission = $request->mission;
        $university->vision = $request->vision;
        $university->motto = $request->motto;
        $university->affiliation = $request->affiliation;
        $university->accreditation = $request->accreditation;
        $university->accreditation_date = $request->accreditation_date;
        $university->accreditation_expiration = $request->accreditation_expiration;
        $university->accreditation_authority = $request->accreditation_authority;
        $university->save();
        //return json response of the newly created model
        return response()->json($university);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\university  $university
     * @return \Illuminate\Http\Response
     */
    public function show(university $university)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\university  $university
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, university $university)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\university  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy(university $university)
    {
        //
    }
}
