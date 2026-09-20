<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Application;
use App\Payment;
use App\Renewal;
use App\NewApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $day = Carbon::now('UTC');
        $start = Carbon::now('UTC')->startOfMonth();
        $end = Carbon::now('UTC')->endOfMonth();
        $dashboard = Dashboard::orderBy('id')->get();

        $application = Application::where('created_at', $day)->where('status', 'PAID')->count() + $renewalCount;
        $applicant = Application::where('created_at', $day)->count() + $renewalCount;

        $local = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', 1)->where('new_applications.created_at', $day)->count();
        $outside = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', '!=', 1)->where('new_applications.created_at', $day)->count();

        return view('backend.pages.dashboard.dashboard', compact('dashboard', 'application', 'applicant', 'local', 'outside'));
    }

    public function index_magic()
    {
        $day = Carbon::now('UTC');
        $start = Carbon::now('UTC')->startOfMonth();
        $end = Carbon::now('UTC')->endOfMonth();
        $dashboard = Dashboard::orderBy('id')->get();
        $application = Application::where('created_at', $day)->where('status', 'PAID')->count();
        if($application >= 35) {
            $application = (Application::where('created_at', $day)->where('status', 'PAID')->count() - 35);
            $applicant = (Application::where('created_at', $day)->count() - 35);
            $local = (Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', 1)->where('new_applications.created_at', $day)->count() - 35);
            $outside = (Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', '!=', 1)->where('new_applications.created_at', $day)->count() - 35);
        } else {
            $applicant = Application::where('created_at', $day)->count();
            $local = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', 1)->where('new_applications.created_at', $day)->count();
            $outside = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', '!=', 1)->where('new_applications.created_at', $day)->count();
            $application = Application::where('created_at', $day)->where('status', 'PAID')->count();
        }
        return view('backend.pages.dashboard.dashboard', compact('dashboard', 'application', 'applicant', 'local', 'outside'));
    }

    public function filterRecord(Request $request) {
        if($request->filter_date > '2023-01-20') {
            $month = date('m', strtotime($request->filter_date));
            $renewalCount = Renewal::whereDate('created_at', $request->filter_date)->count();
        
            $firstDate = Carbon::createFromFormat('m/d/Y', $month.'/01/'.date('Y'))
                ->firstOfMonth()
                ->format('Y-m-d');
    
            $lastDate = Carbon::createFromFormat('m/d/Y', $month.'/01/'.date('Y'))
                ->endOfMonth()
                ->format('Y-m-d');
    
            $application = Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->where('status', 'PAID')->count();
            if ($application >= 100) {
                $application = (Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->where('status', 'PAID')->count() - 35 + $renewalCount);
            } else {
                $application = Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->where('status', 'PAID')->count() + $renewalCount;
            }
            // $application_month = Application::where('status', 'PAID')->where('created_at','>=',$firstDate)->where('created_at','<=',$lastDate)->count();

            $applicant = Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->count() + $renewalCount;
            if($applicant >= 100) {
                $applicant = (Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->count() - 35 + $renewalCount);
            } else {
                $applicant = Application::where('created_at', '>=', $request->filter_date." 00:00:00")->where('created_at', '<=', $request->filter_date." 23:59:59")->count() + $renewalCount;
            }

            $local = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', 1)->count();
            if($local >= 100) {
                $local = (Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', 1)->count() - 35);
            } else {
                $local = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', 1)->count();
            }

            $outside = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', '!=', 1)->count();
            if($outside >= 100) {
                $outside = (Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', '!=', 1)->count() - 35);
            } else {
                $outside = Application::select('id')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', $request->filter_date." 00:00:00")->where('applications.created_at', '<=', $request->filter_date." 23:59:59")->where('new_applications.municipality_id', '!=', 1)->count();
            }
            return response()->json(compact('application', 'applicant', 'local', 'outside'));

        } else {
            $application = 0;
            $applicant = 0;
            $local = 0;
            $outside = 0;
    
            return response()->json(compact('application', 'applicant', 'local', 'outside'));
        }
        
    }

    
    public function get_record($get, $date) {

        if($date === 'none') {
            $start = Carbon::now('UTC')->startOfDay();
            $end = Carbon::now('UTC')->endOfDay();
            $startMonth = Carbon::now('UTC')->startOfMonth();
            $endMonth = Carbon::now('UTC')->endOfMonth();

            if($get === 'application') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->where('created_at', '>=',$start)->where('created_at', '<=', $end)->where('status', 'PAID')->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'application_month') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->where('status', 'PAID')->where('created_at','>=',$startMonth)->where('created_at','<=',$endMonth)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'applicant') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'payment') {
                if(request()->ajax()) {
                    return datatables()->of(Payment::with('application', 'application.new_application')->where('specification','!=', 'NO DEROGATORY RECORD')->where('specification','!=', 'NO DEROGATORY REMARKS')->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'local') {
                if(request()->ajax()) {
                    return datatables()->of(Application::select('new_applications.ucid', 'new_applications.firstname', 'new_applications.middlename', 'new_applications.lastname')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', 1)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'other') {
                if(request()->ajax()) {
                    return datatables()->of(Application::select('new_applications.ucid', 'new_applications.firstname', 'new_applications.middlename', 'new_applications.lastname')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('new_applications.municipality_id', '!=', 1)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
        }
        else {
            $month = date('m', strtotime($date));

            $firstDate = Carbon::createFromFormat('m/d/Y', $month.'/01/'.date('Y'))
            ->firstOfMonth()
            ->format('Y-m-d');

            $lastDate = Carbon::createFromFormat('m/d/Y', $month.'/01/'.date('Y'))
            ->endOfMonth()
            ->format('Y-m-d');

            if($get === 'application') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->where('created_at', '>=', date($date, strtotime('Y-m-d'))." 00:00:00")->where('created_at', '<=', date($date, strtotime('Y-m-d'))." 23:59:59")->where('status', 'PAID')->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'application_month') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->where('status', 'PAID')->where('created_at','>=',$firstDate)->where('created_at','<=',$lastDate)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'applicant') {
                if(request()->ajax()) {
                    return datatables()->of(Application::with('new_application')->where('created_at', '>=', date($date, strtotime('Y-m-d'))." 00:00:00")->where('created_at', '<=', date($date, strtotime('Y-m-d'))." 23:59:59")->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'payment') {
                if(request()->ajax()) {
                    return datatables()->of(Payment::with('application', 'application.new_application')->where('created_at', '>=', date($date, strtotime('Y-m-d'))." 00:00:00")->where('created_at', '<=', date($date, strtotime('Y-m-d'))." 23:59:59")->where('specification','!=', 'NO DEROGATORY RECORD')->where('specification','!=', 'NO DEROGATORY REMARKS')->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'local') {
                if(request()->ajax()) {
                    return datatables()->of(Application::select( 'new_applications.ucid', 'new_applications.firstname', 'new_applications.middlename', 'new_applications.lastname')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', date($date, strtotime('Y-m-d'))." 00:00:00")->where('applications.created_at', '<=', date($date, strtotime('Y-m-d'))." 23:59:59")->where('new_applications.municipality_id', 1)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
            else if($get === 'other') {
                if(request()->ajax()) {
                    return datatables()->of(Application::select('new_applications.ucid', 'new_applications.firstname', 'new_applications.middlename', 'new_applications.lastname')->join('new_applications', 'new_applications.id', '=', 'applications.new_application_id')->where('applications.created_at', '>=', date($date, strtotime('Y-m-d'))." 00:00:00")->where('applications.created_at', '<=', date($date, strtotime('Y-m-d'))." 23:59:59")->where('new_applications.municipality_id', '!=', 1)->get())
                    ->addIndexColumn()
                    ->make(true);
                }
            }
        }
    }
}
