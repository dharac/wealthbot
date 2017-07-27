<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;
use App\Country;
use App\SecurityQuestion;
use Illuminate\Support\Str;

class SubAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::getUserRoleWise(2);
        return view('admin.pages.sub-admin', compact('users'));
    }

    public function newRecord()
    {
        $mode = 'Add';
        $countries  = [ '' => '-- Country --' ] + Country::pluck('counm','coucod')->all();
        return view('admin.pages.new-sub-admin', compact('mode','countries'));
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'first_name'                    => 'required|min:3',
            'username'                      => 'required|unique:users|min:3|alpha_dash',
            'email'                         => 'required|email|unique:users',
            'password'                      => 'required|min:6|confirmed',
            'password_confirmation'         => 'required|min:6',
            'country'                       => 'required',
            ],[
                'first_name.required'   => 'The First name field is required.',
                'username.required'     => 'The Username field is required.',
                'password.confirmed'    => 'The password and Confirm password does not match.',
                'country.required'      => 'The Country field is required.',
        ]);

        $user       = Auth::user();
        $status     = 'active';

        $BW_MESSAGE = ucwords(strtolower($request->first_name));
        
        $user = User::create([
                    'first_name'        => ucwords(strtolower($request->first_name)),
                    'last_name'         => ucwords(strtolower($request->last_name)),
                    'username'          => $request->username,
                    'email'             => $request->email,
                    'coucod'            => $request->country,
                    'status'            => $status,
                    'password'          => bcrypt($request->password),
                    'created_by'        => $user->id,
                    'modified_by'       => $user->id
                ]);

        if($user)
        {
            //PUBLIC USER
            $user->roles()->attach(2);

            $content = [
                'first_name'    =>  ucwords(strtolower($request->first_name)),
                'last_name'     =>  ucwords(strtolower($request->last_name)),
                'email'         =>  $request->email,
                'password'      =>  $request->password,
            ];

            User::sendEmailNotification($content);

            Session::flash('message', 'Success! Sub Admin '.$BW_MESSAGE.' Created.'); 
            Session::flash('alert-class', 'alert-success'); 
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/sub-admin');
    }

    public function editRecord($id)
    {
        $mode = 'Edit';
        $user = User::findOrFail($id);
        $countries  = [ '' => '-- Country --' ] + Country::pluck('counm','coucod')->all();
        return view('admin.pages.new-sub-admin',compact('mode','user','countries'));
    }

    public function update(Request $request)
    {
        $validator = $this->validate($request, [
            'first_name'                    => 'required|min:3',
            'country'                       => 'required',
            ],[
                'first_name.required'       => 'The First name field is required.',
                'country.required'          => 'The Country field is required.',
        ]);

        $user_id = $request->eid;
        $user_data = User::findOrFail($user_id);

        $BW_MESSAGE = ucwords(strtolower($request->first_name)) .' '.ucwords(strtolower($request->last_name));

        $user = $user_data->update([
                'first_name'        => ucwords(strtolower($request->first_name)),
                'last_name'         => ucwords(strtolower($request->last_name)),
                'coucod'            => $request->country,
                'modified_by'       => Auth::user()->id
            ]);

        if($user)
        {
            Session::flash('message', 'Success! Sub Admin '.$BW_MESSAGE.' Updated.'); 
            Session::flash('alert-class', 'alert-success'); 
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/sub-admin');
    }


    // public function delete(Request $request)
    // {
    //     $id = $request->id;
    //     $user_data = User::findOrFail($id);
    //     $user_data->delete();
    //     echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
    // }

}
