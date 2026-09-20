<?php

namespace App\Http\Controllers;

use App\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function index()
    {
        $municipalities = Municipality::orderBy('id', 'desc')->get();
        return view('backend.pages.maintenance.municipality', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $municipality = $request->validate([
            'municipality' => ['required', 'max:250', 'unique:municipalities'],
        ]);

        Municipality::create($request->all());
        return redirect()->back()->with('success','Successfully Added');
    }

    public function edit($id)
    {
        $municipalities = Municipality::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('municipalities'));
    }

    public function update(Request $request, $id)
    {
        Municipality::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $municipality = Municipality::find($id);
        $municipality->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
}
