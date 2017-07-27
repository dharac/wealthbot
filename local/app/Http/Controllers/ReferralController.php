<?php
namespace App\Http\Controllers;
use App\Referral;
use App\LevelCommision;
use Illuminate\Http\Request;
use Response;
use Auth;
use App\User;

class ReferralController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
    public function index($id = null)
    {	
        $loginUsr = $id;
        if($id == null || $id == "")
        {
            $loginUsr   = Auth::user()->id;
        }
        $user       = User::findOrFail($loginUsr);
        $referrals  = Referral::getReferralDownline($loginUsr,1,10,'geneology');
        return view('users.pages.referral', compact('referrals','user'));
    }
}
