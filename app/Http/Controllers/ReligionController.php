<?php

namespace App\Http\Controllers;

use App\Religion;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    public function index()
    {
        $religions = Religion::orderBy('id', 'desc')->get();
        return view('backend.pages.maintenance.religion', compact('religions'));
    }

    public function store(Request $request)
    {
        $religion = $request->validate([
            'religion' => ['required', 'max:250', 'unique:religions'],
        ]);

        Religion::create($request->all());
        return redirect()->back()->with('success','Successfully Added');
    }

    public function edit($id)
    {
        $religions = Religion::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('religions'));
    }

    public function update(Request $request, $id)
    {
        Religion::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $religion = Religion::find($id);
        $religion->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
}
