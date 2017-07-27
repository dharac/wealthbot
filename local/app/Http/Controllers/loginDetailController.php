<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\LoginDetail;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Carbon\Carbon;

class loginDetailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$dateRange = "";
		$stdt = "";
		$endt = "";
		$perPage = config('services.DATATABLE.PERPAGE');
		if($request->startdt != null && $request->enddt != null)
		{
			$mode 		= 'search';
			$startdt  	= Carbon::parse($request->startdt)->format('m-d-Y');
			$startdt 	= Carbon::createFromFormat('m-d-Y', $startdt);
			$enddt  	= Carbon::parse($request->enddt)->format('m-d-Y');
			$enddt 		= Carbon::createFromFormat('m-d-Y', $enddt);

			$stdt = $request->startdt;
			$endt = $request->enddt;

			$loginDetails = LoginDetail::join('users', 'logindetails.created_by', '=', 'users.id')
			->whereDate('logindetails.created_at', '>=', $startdt->toDateString())
	        ->whereDate('logindetails.created_at', '<=', $enddt->toDateString())
	        ->orderby('logindetails.created_at','asc')
			->select('users.first_name','users.last_name','users.username','logindetails.*')
			->paginate($perPage);

	        $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';
			$loginDetails = LoginDetail::join('users', 'logindetails.created_by', '=', 'users.id')
			->select('users.first_name','users.last_name','users.username','logindetails.*')
			->latest()
			->paginate($perPage);
		}
		return view('admin.pages.login-detail', compact('loginDetails','dateRange','mode','stdt','endt'));
	}
}
