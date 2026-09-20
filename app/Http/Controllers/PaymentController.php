<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Application;
use App\NewApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::orderBy('id', 'desc')->get();
        return view('backend.pages.payment.payment', compact('payments'));

    }

    public function process()
    {
        $applications = Application::where('status', 'ON-PROCESS')->orderBy('id', 'desc')->with('new_application')->get();
        $last_id = Payment::orderBy('id', 'desc')->first();
        $id = ($last_id === NULL) ? $code = 1 : $code = $last_id->id + 1;
        $or_number = 'OR-'.Carbon::today()->format('mdY').'-'. str_pad($code + 2000, 7, '0', STR_PAD_LEFT);
        return view('backend.pages.payment.addpayment', compact('applications', 'or_number'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $payment_record = $request->validate([
            'application_id' => [ 'max:250'],
            'specification' => [ 'max:250'],
            'or_number' => [ 'max:250'],
            'ucid' => [ 'max:250'],
            'date_of_expiration' => [ 'max:250'],
            'amount' => [ 'max:250'],
            'status' => [ 'max:250']
        ]);

        $payment = Payment::create($request->all());

        Application::where('id', $request->application_id)->update(['status'=>'PAID']);
        return redirect()->back()->with('success','Successfully Added');
    }

    public function show(Payment $payment)
    {
        //
    }

    public function edit(Payment $payment)
    {
        //
    }

    public function update(Request $request, Payment $payment)
    {
        //
    }

    public function destroy(Payment $payment)
    {
        //
    }
}
