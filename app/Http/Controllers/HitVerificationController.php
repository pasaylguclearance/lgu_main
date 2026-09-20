<?php

namespace App\Http\Controllers;

use App\HitVerification;
use Illuminate\Http\Request;

class HitVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.pages.main.hitverification');
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
     * @param  \App\HitVerification  $hitVerification
     * @return \Illuminate\Http\Response
     */
    public function show(HitVerification $hitVerification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HitVerification  $hitVerification
     * @return \Illuminate\Http\Response
     */
    public function edit(HitVerification $hitVerification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HitVerification  $hitVerification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HitVerification $hitVerification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HitVerification  $hitVerification
     * @return \Illuminate\Http\Response
     */
    public function destroy(HitVerification $hitVerification)
    {
        //
    }
}
