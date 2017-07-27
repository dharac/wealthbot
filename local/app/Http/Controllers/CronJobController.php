<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\CronJob;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;

class CronJobController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
		$crons = CronJob::latest()->paginate($perPage);
		return view('admin.pages.cron-job', compact('crons'));
	}
}
