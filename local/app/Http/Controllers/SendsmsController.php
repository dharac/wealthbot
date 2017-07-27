<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SendSms;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\myCustome\myCustome;

class SendsmsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
    {
        $perPage = config('services.DATATABLE.PERPAGE');


        if($request->q == "")
        {
            $q = "";
            $smss = SendSms::join('users', 'send_sms.created_by', '=', 'users.id')
            ->select('users.first_name','users.last_name','users.username','users.id', 'send_sms.*')
            ->orderby('send_sms.created_at','desc')
            ->paginate($perPage);
        }
        else
        {
            $q = $request->q;

            $smss = SendSms::join('users', 'send_sms.created_by', '=', 'users.id')
            ->select('users.first_name','users.last_name','users.username','users.id', 'send_sms.*')
            ->orwhere('send_sms.message_id', 'like','%'.$q.'%')
            ->orWhereRaw("concat(users.first_name, ' ', users.last_name) like '%".$q."%' ")
            ->orwhere('users.username', 'like','%'.$q.'%')
            ->orderby('send_sms.created_at','desc')
            ->paginate($perPage);
        }
        return view('admin.pages.send-sms', compact('smss','q'));
    }
}
?>