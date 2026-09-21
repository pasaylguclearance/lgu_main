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
        // OR-number sequence must consider ALL payments, not just the ones this account may see.
        $last_id = Payment::unrestricted()->orderBy('id', 'desc')->first();
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
        // An application must be picked from the "Application No" search before
        // a payment can be recorded — otherwise application_id arrives empty and
        // the NOT NULL column throws a 23000 integrity error.
        $payment_record = $request->validate([
            // Scoped model lookup (not a raw exists: rule) so an account with a
            // visibility window cannot pay against a record it is not allowed to see.
            'application_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if (!Application::where('id', $value)->exists()) {
                    $fail('The selected application no longer exists or is not on process.');
                }
            }],
            'specification' => ['nullable', 'max:250'],
            'or_number' => ['required', 'max:250'],
            'ucid' => ['nullable', 'max:250'],
            'date_of_expiration' => ['nullable', 'max:250'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'max:250']
        ], [
            'application_id.required' => 'Please select an Application No first.',
            'application_id.exists' => 'The selected application no longer exists or is not on process.',
            'amount.required' => 'Please enter the payment amount.',
        ]);

        // specification / ucid / date_of_expiration are NOT NULL strings in the
        // payments table but optional on the form; blank inputs arrive as null.
        $payment = Payment::create([
            'application_id'     => $request->application_id,
            'specification'      => (string) $request->input('specification', ''),
            'or_number'          => (string) $request->input('or_number', ''),
            'ucid'               => (string) $request->input('ucid', ''),
            'date_of_expiration' => (string) $request->input('date_of_expiration', ''),
            'amount'             => (string) $request->input('amount', ''),
        ]);

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
