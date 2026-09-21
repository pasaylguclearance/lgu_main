<?php

namespace App\Http\Controllers;

use App\Application;
use App\NewApplication;
use App\Purpose;
use App\Municipality;
use App\Renewal;
use App\Religion;
use App\Nationality;
use Illuminate\Http\Request;
use DB;
use Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::where('status', '!=', 'PAID')->orderBy('id', 'desc')->with('new_application')->limit(500)->paginate(25);
        // $applications = Application::where('status', '!=', 'PAID')->orderBy('id', 'desc')->with('new_application')->get();
        $applicants = NewApplication::orderBy('id', 'desc')->limit(500)->get();
        return view('backend.pages.application.application', compact('applications', 'applicants'));
    }

    public function completed()
    {
        $purposes = Purpose::orderBy('id')->get();
        $municipalities = Municipality::orderBy('id')->get();
        $religions = Religion::orderBy('id')->get();
        $nationalities = Nationality::orderBy('id')->get();

        $applications = Application::where('status', 'PAID')->orderBy('id', 'desc')->with('new_application')->limit(500)->get();
        return view('backend.pages.application.completed', compact('applications', 'purposes', 'municipalities', 'religions', 'nationalities'));
    }

    public function completed_record()
    {
        $purposes = Purpose::orderBy('id')->get();
        $municipalities = Municipality::orderBy('id')->get();
        $religions = Religion::orderBy('id')->get();
        $nationalities = Nationality::orderBy('id')->get();

        $applications = Application::where('status', 'PAID')->orderBy('id', 'desc')->with('new_application')->limit(100)->get();
        return view('backend.pages.application.completed_record', compact('applications', 'purposes', 'municipalities', 'religions', 'nationalities'));
    }

    public function completed_edit($id)
    {
        $record = Application::where('id', $id)->firstOrFail();
        $application = NewApplication::where('id', $record->new_application_id)->firstOrFail();
        return response()->json(compact('application'));
    }

    public function completed_update(Request $request, $id)
    {
        NewApplication::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function dashboard_api()
    {
        $dashboard_api = Application::orderBy('id')->with('new_application', 'payment')->get();
        return response()->json(compact('dashboard_api'));
    }

    public function detail()
    {
        return view('backend.pages.application.applicant_detail');
    }

    public function printableForm(Request $request)
    {
        $applicant = null;
        if ($request->filled('id')) {
            $applicant = NewApplication::with(['purpose', 'nationality', 'religion'])
                ->where('id', $request->id)
                ->first();
        }

        return view('backend.pages.application.printable_form', compact('applicant'));
    }

    // public function masterlist_applicant()
    // {
    //     if(request()->ajax()) {
    //         return datatables()->of(Application::where('status', 'PAID')->orderBy('id')->with('new_application')->get())
    //         ->addIndexColumn()
    //         ->make(true);
    //     }

    //     return response()->json(compact('applications'));
    // }

    public function masterlist_applicant()
{
    if(request()->ajax()) {
        return datatables()->of(Application::where('status', 'PAID')->orderBy('id', 'desc')->with('new_application')->take(500)->get())
            ->addIndexColumn()
            ->make(true);
    }

    return response()->json(compact('applications'));
}

    public function masterlist_applicant_magic()
    {
        if(request()->ajax()) {
            return datatables()->of(
                Application::where('status', 'PAID')->whereHas('new_application')->orderBy('id', 'desc')->with('new_application', 'renew')->limit(10)->get()
            )
            ->addIndexColumn()
            ->make(true);
        }

        return response()->json(['data' => []]);
    }
    
    public function masterlist_applicant_filter(Request $request)
    {
        $total_application = Application::where('status', 'PAID')->orderBy('id')->where('created_at', '>', '2023-01-20')->with('new_application')->count();
        $total_date = Application::select('date')->where('created_at', '>=', '2023-01-20')->groupBy('date')->get();
        $total_no_days = $total_date->count();
        $number_data = $total_application - (($total_no_days - 1) * 35);
        $fname = trim((string) $request->input('fname', ''));
        $mname = trim((string) $request->input('mname', ''));
        $lname = trim((string) $request->input('lname', ''));

        if(request()->ajax()) {
            return datatables()->of(
                Application::where('status', 'PAID')
                    ->orderBy('id', 'desc')
                    ->with('new_application', 'renew')
                    ->whereHas('new_application', function($query) use($fname, $mname, $lname) {
                        $query->when($fname !== '', function ($subQuery) use ($fname) {
                            $subQuery->where('firstname', 'like', '%'.$fname.'%');
                        })->when($mname !== '', function ($subQuery) use ($mname) {
                            $subQuery->where('middlename', 'like', '%'.$mname.'%');
                        })->when($lname !== '', function ($subQuery) use ($lname) {
                            $subQuery->where('lastname', 'like', '%'.$lname.'%');
                        });
                    })
                    ->limit(10)
                    ->get()
            )
            ->addIndexColumn()
            ->make(true);
        }

        return response()->json(compact('applications'));
    }

    public function list_applicant()
    {
        if(request()->ajax()) {
            return datatables()->of(NewApplication::where('status', '!=', 'PAID')->orderBy('id', 'desc')->limit(1000)->get())
            ->addIndexColumn()
            ->make(true);
        }

        return response()->json(compact('applicants'));
    }
    
    public function list_applicant_filter(Request $request)
    {
        $fname = trim((string) $request->input('fname', ''));
        $mname = trim((string) $request->input('mname', ''));
        $lname = trim((string) $request->input('lname', ''));

        if(request()->ajax()) {
            return datatables()->of(
                NewApplication::where('status', '!=', 'PAID')
                    ->when($fname !== '', function ($query) use ($fname) {
                        $query->where('firstname', 'like', '%'.$fname.'%');
                    })
                    ->when($mname !== '', function ($query) use ($mname) {
                        $query->where('middlename', 'like', '%'.$mname.'%');
                    })
                    ->when($lname !== '', function ($query) use ($lname) {
                        $query->where('lastname', 'like', '%'.$lname.'%');
                    })
                    ->orderBy('id', 'desc')
                    ->limit(1000)
                    ->get()
            )
            ->addIndexColumn()
            ->make(true);
        }

        return response()->json(compact('applicants'));
    }

    public function getApplication($id) {
        $application = NewApplication::with('renew')->where('id', $id)->first();

        return response()->json(compact('application'));
    }

    public function picture($id)
    {
        $latest_record = NewApplication::where('id', $id)->first();
        return view('backend.partial.new_application.picture', compact('latest_record'));
    }

    public function right_thumb($id)
    {
        $latest_record = NewApplication::where('id', $id)->first();
        return view('backend.partial.new_application.fingerprint_right', compact('latest_record'));
    }

    public function left_thumb($id)
    {
        $latest_record = NewApplication::where('id', $id)->first();
        return view('backend.partial.new_application.fingerprint_left', compact('latest_record'));
    }

    public function signature($id)
    {
        $latest_record = NewApplication::where('id', $id)->first();
        return view('backend.partial.new_application.signature', compact('latest_record'));
    }

    public function store(Request $request)
    {
        $application = $request->validate([
            // Scoped lookup: an account with a visibility window cannot attach a
            // transaction to an applicant it is not allowed to see.
            'new_application_id' => ['required', function ($attribute, $value, $fail) {
                if (!NewApplication::where('id', $value)->exists()) {
                    $fail('The selected applicant was not found.');
                }
            }],
            'type' => ['required', 'max:250'],
            'date' => ['required', 'max:250'],
        ]);

        Application::create($request->all());
        return redirect()->back()->with('success','Successfully Added');
    }

    public function certificate($id)
    {
        $application = Application::where('id', $id)->orderBy('id')->with('new_application', 'renew', 'new_application.purpose',
            'new_application.municipality', 'new_application.nationality', 'new_application.religion')->firstOrFail();
        return response()->json(compact('application'));
    }

    public function edit($id)
    {
        $applications = Application::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('applications'));
    }

    public function update(Request $request, $id)
    {
        Application::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        $application->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }
    
    public function renewApplication(Request $request) {
        
        $application = $request->validate([
            'application_id' => ['required', function ($attribute, $value, $fail) {
                if (!NewApplication::where('id', $value)->exists()) {
                    $fail('The selected applicant was not found.');
                }
            }],
            'date_renew' => ['required'],
            'date_expiry' => ['required']
        ]);

        $request->request->add(['created_by' => Auth::user()->id]);
        $request->request->add(['updated_by' => Auth::user()->id]);

        Renewal::create($request->all());

        return response()->json(compact('application'));
    }
}
