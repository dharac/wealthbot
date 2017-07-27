<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Carbon\Carbon;
use App\ReDeposit;
use App\User;

class RedepositeController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$data 		= array();
		$deposits 	= array();
		$stdate  	= "";
		$endate  	= "";
		$cmb_user 	= "";

		return view('admin.pages.ledger',compact('data','stdate','endate','deposits','cmb_user'));
	}

	public function detail(Request $request)
	{
		$validator = $this->validate($request, [
	        'stdate' 	=> 'nullable|date',
	        'endate' 	=> 'nullable|date',
	        'cmb_user' 	=> 'required',
	    	],[
	    		'stdate.date'		=> 'The Start date is invalid.',
	    		'endate.date'		=> 'The End date date is invalid.',
	    		'cmb_user.required'	=> '* The User field is required.',
	   	]);

		$stdate = "";
		$endate = "";
		$cmb_user  = $request->cmb_user;
		$passPara = 'user';
		if($request->stdate != null || $request->endate != null)
		{
			$stdate  	= Carbon::parse($request->stdate)->format('Y-m-d');
			$stdate 	= Carbon::createFromFormat('Y-m-d', $stdate);
	   		$endate  	= Carbon::parse($request->endate)->format('Y-m-d');
	   		$endate 	= Carbon::createFromFormat('Y-m-d', $endate);
	   		$passPara 	= 'admin';
		}

        $result  = ReDeposit::getDetails($stdate,$endate,$cmb_user,$passPara);
        $deposits   = $result['result1'];
        $data       = $result['result2'];
        $cmb_user  = $request->cmb_user;

	   	$stdate    = $request->stdate;
	   	$endate    = $request->endate;
	   	return view('admin.pages.ledger',compact('data','stdate','endate','deposits','cmb_user'));
	}

    public function indexUser()
    {
        $id         = Auth::user()->id;
        $cmb_user   = $id;
        $result     = ReDeposit::getDetails('','',$id,'user');
        $deposits   = $result['result1'];
        $data       = $result['result2'];
        return view('admin.pages.ledger',compact('data','deposits','users','cmb_user'));
    }
}
