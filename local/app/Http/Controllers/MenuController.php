<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\Menu;
use App\Page;

class MenuController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
        $menus = Menu::paginate($perPage);
		return view('admin.pages.menu', compact('menus'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		$pages 	= Page::pluck('title','pageid')->all();
		return view('admin.pages.new-menu', compact('mode','pages'));	
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'menu_name' 	=> 'required',
	        'pages' 	=> 'required',
	    	],[
	    		'merchant_id.required'=> 'The Merchant Id field is required.',
	    		'public_id.required'=> 'The Public Id field is required.',
	   	]);

		$str 			= implode(",",$request->pages);
		$menu_name_slug = str_slug($request->menu_name);

		$insert = Menu::create([
					'menu_name' 		=> $request->menu_name,
					'page_ids' 			=> $str,
					'menu_unique_name' 	=> $menu_name_slug,
					'created_by'		=> Auth::user()->id,
					'modified_by'		=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->menu_name;

		if($insert)
		{
			Session::flash('message', 'Success! Menu '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/menu');
	}

	public function editRecord($id)
	{
		$mode 	= 'Edit';
		$menu 	= Menu::findOrFail($id);
		$pages 	= Page::pluck('title','pageid')->all();
		return view('admin.pages.new-menu', compact('mode','menu','pages'));
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'menu_name' 	=> 'required',
	        'pages' 	=> 'required',
	    	],[
	    		'merchant_id.required'=> 'The Merchant Id field is required.',
	    		'public_id.required'=> 'The Public Id field is required.',
	   	]);

		$str = implode(",",$request->pages);

	   	$id 	= $request->eid;
		$data 	= Menu::findOrFail($id);

		$menu_name_slug = str_slug($request->menu_name);

		$update = $data->update([
				'menu_name' 		=> $request->menu_name,
				'page_ids' 			=> $str,
				'menu_unique_name' 	=> $menu_name_slug,
				'modified_by'		=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->menu_name;

		if($update)
		{
			Session::flash('message', 'Success! Menu '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/menu');
	}
}
