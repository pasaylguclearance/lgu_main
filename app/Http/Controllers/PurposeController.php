<?php

namespace App\Http\Controllers;

use App\Purpose;
use Illuminate\Http\Request;

class PurposeController extends Controller
{
    public function index()
    {
        $purposes = Purpose::orderBy('id', 'desc')->get();
        return view('backend.pages.maintenance.purpose', compact('purposes'));
    }

    public function store(Request $request)
    {
        $purpose = $request->validate([
            'purpose' => ['required', 'max:250', 'unique:purposes'],
            'cost' => ['required', 'max:250'],
        ]);

        Purpose::create($request->all());
        return redirect()->back()->with('success','Successfully Added');
    }

    public function edit($id)
    {
        $purposes = Purpose::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('purposes'));
    }

    public function update(Request $request, $id)
    {
        Purpose::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $purpose = Purpose::find($id);
        $purpose->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
}
