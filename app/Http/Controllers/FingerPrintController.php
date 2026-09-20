<?php

namespace App\Http\Controllers;

use App\FingerPrint;
use Illuminate\Http\Request;

class FingerPrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.pages.fingerprint.fingerprint');
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
     * @param  \App\FingerPrint  $fingerPrint
     * @return \Illuminate\Http\Response
     */
    public function show(FingerPrint $fingerPrint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FingerPrint  $fingerPrint
     * @return \Illuminate\Http\Response
     */
    public function edit(FingerPrint $fingerPrint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FingerPrint  $fingerPrint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FingerPrint $fingerPrint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FingerPrint  $fingerPrint
     * @return \Illuminate\Http\Response
     */
    public function destroy(FingerPrint $fingerPrint)
    {
        //
    }
}
