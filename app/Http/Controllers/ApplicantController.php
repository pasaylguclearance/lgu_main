<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applicants = Applicant::orderBy('id', 'desc')->get();
        return view('backend.pages.main.applicants', compact('applicants'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $applicant = $request->validate([
            'firstname' => ['required', 'max:250'],
            'middlename' => ['required', 'max:250'],
            'lastname' => ['required', 'max:250'],
            'house_no' => ['required'],
            'barangay' => ['required', 'max:250'],
            'municipality' => ['required', 'max:250'],
            'province' => ['required', 'max:250'],
            'zipcode' => ['required', 'max:250'],
            'country' => ['required', 'max:250'],

            'place_of_birth' => ['required', 'max:250'],
            'nationality' => ['required', 'max:250'],
            'email' => ['required', 'email', 'max:250'],
            'contact' => ['required', 'max:250'],
            'date_of_birth' => ['required', 'max:250'],
            'gender' => ['required', 'max:250'],
            'civil_status' => ['required', 'max:250'],

            'tin' => ['max:250'],
            'sss' => ['max:250'],
            'pagibig' => ['max:250'],
            'philhealth' => ['max:250'],

        ]);

        Applicant::create($request->all());

        return redirect()->back()->with('success','Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function show(Applicant $applicant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $applicants = Applicant::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('rates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Applicant::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $applicant_destroy = Applicant::find($id);
        $applicant_destroy->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
}
