<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id')->get();
        return view('backend.pages.system.users', compact('users'));
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
        $user = $request->validate([
            'firstname' => ['required', 'max:250'],
            'middlename' => ['required', 'max:250'],
            'lastname' => ['required', 'max:250'],
            'suffix' => ['required', 'max:250'],
            'email' => ['required', 'max:250', 'unique:users'],

        ]);

        $request['profile_img'] = 'backend/img/avatars/avatar.jpg';
        $request['password'] = Hash::make('password');

        User::create($request->all());
        return redirect()->back()->with('success', 'Successfully Added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::find($id)->update($request->all());
        return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function update_status_active($id)
    {
        if($id == 1) {
            User::find(Auth::user()->id)->update(['status' => 1]);
        } else {
            User::find(Auth::user()->id)->update(['status' => 0]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_destroy = User::find($id);
        $user_destroy->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');

    }

    public function updatePicture(Request $request) {
        $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request['profile_img']));
        $folderName = 'public/images/profile_pictures/';
        $safeName = 'profile_'.$request['id'].'.'.'jpg';
        $destinationPath = public_path() . $folderName;
        $success = file_put_contents(public_path().'/images/profile_pictures/'.$safeName, $file);

        User::where('id', $request['id'])->update(array('profile_img'=> '/images/profile_pictures/'.$safeName));
        return $success;
    }

    public function changePassword(Request $request) {

        $password = User::where('id', $request->id)->first()->password;
        $status = '';

        if(Hash::check($request->current, $password)) {
            if(strlen($request->new) <= 7) {
                $status = 'Invalid Password, The Password must be at least 8 characters long.';
            }
            else {
                if($request->new !== $request->confirm) {
                    $status = 'New Password and Confirm Password does not match.';
                }
                else {
                    User::where('id', $request->id)->update(array('password'=> Hash::make($request->new)));
                    $status = 'success';
                }
            }
        }
        else {
            $status = "Password is incorrect.";
        }

        return $status;
    }
}
