<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Mail;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Cookie;
use Response;


class TrackController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {

    }

    public function track($username = null)
    {
        $user =User::getReferralNm($username);
        if(count($user) > 0)
        {
            if(count($user) > 0)
            {
                $hours = 60*30*24; // FOR 30 DAYS
                $wealthbot_tok_ref = Cookie::make('WEALTHBOT_REF_TOKEN', $user->id,$hours);
                return redirect('home')->withCookie($wealthbot_tok_ref);
            }
            else
            {
                return response()->view('errors.track-error');
            }
        }
        else
        {
            return response()->view('errors.track-error');
        }
    }

}
