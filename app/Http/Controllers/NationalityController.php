<?php

namespace App\Http\Controllers;

use App\Nationality;
use Illuminate\Http\Request;

class NationalityController extends Controller
{
    public function index()
    {
        $nationalities = Nationality::orderBy('id', 'desc')->get();
        return view('backend.pages.maintenance.nationality', compact('nationalities'));
    }

    public function store(Request $request)
    {
        $nationality = $request->validate([
            'nationality' => ['required', 'max:250', 'unique:nationalities'],
        ]);

        Nationality::create($request->all());
        return redirect()->back()->with('success','Successfully Added');
    }

    public function edit($id)
    {
        $nationalities = Nationality::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('nationalities'));
    }

    public function update(Request $request, $id)
    {
        Nationality::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $nationality = Nationality::find($id);
        $nationality->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
}
