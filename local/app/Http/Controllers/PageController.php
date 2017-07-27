<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Page;
use Auth;
use Session;
use Redirect;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');
        $pages = Page::paginate($perPage);
        return view('admin.pages.page',compact('pages'));
    }

    public function newRecord()
    {
        $mode = 'Add';
        return view('admin.pages.new-page',compact('mode'));
    }


    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'title'     => 'required',
            ],[
                'title.required'=> 'The Title Name field is required.'
        ]);

        $excerpt = strip_tags(Str::words($request->pageEditor,12));

        $contentData = $request->pageEditor;
        if($request->type == 'url')
        {
            $contentData = $request->page_url;
        }

        $insert = Page::create([
                    'title'             => $request->title,
                    'content'           => $contentData,
                    'content_type'      => $request->type,
                    'excerpt'           => $excerpt,
                    'created_by'        => Auth::user()->id,
                    'modified_by'       => Auth::user()->id,
                ]);

        $BW_MESSAGE = $request->title;

        if($insert)
        {
            Session::flash('message', 'Success! Page '.$BW_MESSAGE.' Created.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/page');
    }

    public function editRecord($id)
    {
        $mode = 'Edit';
        $page = Page::findOrFail($id);
        return view('admin.pages.new-page',compact('mode','page'));
    }

    public function update(Request $request)
    {
        $validator = $this->validate($request, [
            'title'     => 'required',
            ],[
                'title.required'=> 'The Title Name field is required.'
        ]);

        $id = $request->eid;
        $data = Page::findOrFail($id);

        $excerpt = strip_tags(Str::words($request->pageEditor,12));

        $contentData = $request->pageEditor;
        if($request->type == 'url')
        {
            $contentData = $request->page_url;
        }

        $update = $data->update([
                'title'             => $request->title,
                'content'           => $contentData,
                'content_type'      => $request->type,
                'excerpt'           => $excerpt,
                'modified_by'       => Auth::user()->id,
            ]);

        $BW_MESSAGE = $request->title;

        if($update)
        {
            Session::flash('message', 'Success! Page '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }

        return Redirect::to('admin/page');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = Page::findOrFail($id);
        $data->delete();
        echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
    }
}
