<?php
namespace App\Http\Controllers;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');
        $news = News::latest()->paginate($perPage);
        return view('admin.pages.news', compact('news'));
    }

    public function newRecord()
    {
        $mode = 'Add';
        return view('admin.pages.new-news', compact('mode'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'news_header' 	    => 'required|string',
            'news_description' 	=> 'required|string',
            'status' 	        => 'required',
        ],[
            'news_header.required'=> 'The News Header field is required.',
            'news_description.required'=> 'The News Description field is required.',
        ]);

        $excerpt = strip_tags(Str::words($request->news_description,12));

        $News = News::create([
            'news_header' 		=> $request->news_header,
            'news_description' 	=> $request->news_description,
            'excerpt'           => $excerpt,
            'status' 			=> $request->status,
            'created_by'		=> Auth::user()->id,
            'modified_by'		=> Auth::user()->id,
        ]);

        $BW_MESSAGE = $request->news_header;

        if($News)
        {
            Session::flash('message', 'Success! News '.$BW_MESSAGE.' Created.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/news');
    }

    public function editRecord($id)
    {
        $mode = 'Edit';
        $news = News::findOrFail($id);
        return view('admin.pages.new-news', compact('mode','news'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'news_header' 	    => 'required|string',
            'news_description' 	=> 'required|string',
            'status' 	        => 'required',
        ],[
            'news_header.required'=> 'The News Header field is required.',
            'news_description.required'=> 'The News Description field is required.',
        ]);

        $id = $request->eid;
        $data = News::findOrFail($id);

        $excerpt = strip_tags(Str::words($request->news_description,12));

        $update = $data->update([
            'news_header' 		=> $request->news_header,
            'news_description' 	=> $request->news_description,
            'excerpt'           => $excerpt,
            'status' 			=> $request->status,
            'modified_by'		=> Auth::user()->id,
        ]);

        $BW_MESSAGE = $request->news_header;

        if($update)
        {
            Session::flash('message', 'Success! News '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/news');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = News::findOrFail($id);
        $data->delete();
        echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
    }

}
