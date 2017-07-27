<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\LevelCommision;
use App\Notifications;
use App\Referral;
use Redirect;
use Auth;
use Carbon\Carbon;
use Session;
use App\myCustome\myCustome;
use App\User;
use App\ReDeposit;
use App\Deposit;

class LevelCommisionController extends Controller
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

			$levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.created_by')
			->join('users as userdwn', 'userdwn.id', '=', 'level_commisions.downlineid')
	        ->whereDate('level_commisions.created_at', '>=', $startdt->toDateString())
	        ->whereDate('level_commisions.created_at', '<=', $enddt->toDateString())
	        ->orderby('level_commisions.created_at','asc')
	        ->select('level_commisions.*','users.id','users.first_name','users.last_name','users.username','userdwn.username as down_username','userdwn.first_name as down_first_name','userdwn.last_name as down_last_name')
	        ->latest()
	        ->paginate($perPage);

	        $totalAmt = LevelCommision::whereDate('level_commisions.created_at', '>=', $startdt->toDateString())
	        ->whereDate('level_commisions.created_at', '<=', $enddt->toDateString())
	        ->sum('commission');

	        $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';    
		    $levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.created_by')
		    ->join('users as userdwn', 'userdwn.id', '=', 'level_commisions.downlineid')
	        ->select('level_commisions.*','users.id','users.first_name','users.last_name','users.username','userdwn.username as down_username','userdwn.first_name as down_first_name','userdwn.last_name as down_last_name')
	        ->latest()
	        ->paginate($perPage);

	        $totalAmt = LevelCommision::sum('commission');
    	}
		
		return view('admin.pages.level-commision', compact('levelCommisions','dateRange','mode','stdt','endt','totalAmt'));
	}

	public function export(Request $request)
	{
		$levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.created_by')
		->join('users as userdwn', 'userdwn.id', '=', 'level_commisions.downlineid')
		->select('level_commisions.*','users.id','users.first_name','users.last_name','users.username','userdwn.username as down_username','userdwn.first_name as down_first_name','userdwn.last_name as down_last_name')
		->orderby('created_by','desc')
		->get();

        $column 	= []; 
        $column[] 	= ['No', 'Referrer','Referrer Username','Referee','Referee Username','Commission Earned Date','Amount ($)','Commission %','Commission Earned ($)'];

        $a = 1;
        $temp = "";
        $total = 0;
        $finalTotal = 0;
        foreach ($levelCommisions as $levelCommision)
        {
            if($temp == "")
            {
            	$temp = $levelCommision->id;
            }

            if($temp == $levelCommision->id)
            {
            	$column[] = [
	            'no'                => $a,
	            'Referrer'          => ucfirst($levelCommision->first_name).' '.ucfirst($levelCommision->last_name),
	            'Referrer Username' => $levelCommision->username,
	            'Referee'           => ucfirst($levelCommision->down_first_name).' '.ucfirst($levelCommision->down_last_name),
	            'Referee Username'  => $levelCommision->down_username,
	            'comm_earn'         => $levelCommision->created_at->toDayDateTimeString(),
	            'amount'            => '$ '.number_format($levelCommision->interest,2),
	            'com_rate'          => number_format($levelCommision->com_rate,2).' %',
	            'commission'        => '$ '.number_format($levelCommision->commission,2),
	            ];

	            $total = $levelCommision->commission + $total;

	            $finalTotal = $levelCommision->commission + $finalTotal;
            }
            else
            {
				$column[] = [
	            'no'                => "",
	            'Referrer'          => "",
	            'Referrer Username' => "",
	            'Referee'           => "",
	            'Referee Username'  => "",
	            'comm_earn'         => "",
	            'amount'            => "",
	            'com_rate'          => "Total",
	            'commission'        => '$ '.number_format($total,2),
	            ];

	            $temp = $levelCommision->id;
	            $total = 0;

	            $column[] = [
	            'no'                => $a,
	            'Referrer'          => ucfirst($levelCommision->first_name).' '.ucfirst($levelCommision->last_name),
	            'Referrer Username' => $levelCommision->username,
	            'Referee'           => ucfirst($levelCommision->down_first_name).' '.ucfirst($levelCommision->down_last_name),
	            'Referee Username'  => $levelCommision->down_username,
	            'comm_earn'         => $levelCommision->created_at->toDayDateTimeString(),
	            'amount'            => '$ '.number_format($levelCommision->interest,2),
	            'com_rate'          => number_format($levelCommision->com_rate,2).' %',
	            'commission'        => '$ '.number_format($levelCommision->commission,2),
	            ];

	            $total = $levelCommision->commission + $total;

	            $finalTotal = $levelCommision->commission + $finalTotal;
            }
            $a++;
        }

        if(count($levelCommisions) > 0)
        {
        	$column[] = [
	            'no'                => "",
	            'Referrer'          => "",
	            'Referrer Username' => "",
	            'Referee'           => "",
	            'Referee Username'  => "",
	            'comm_earn'         => "",
	            'amount'            => "",
	            'com_rate'          => "Total",
	            'commission'        => '$ '.number_format($total,2),
	            ];

	            $column[] = [
	            'no'                => "",
	            'Referrer'          => "",
	            'Referrer Username' => "",
	            'Referee'           => "",
	            'Referee Username'  => "",
	            'comm_earn'         => "",
	            'amount'            => "",
	            'com_rate'          => "",
	            'commission'        => "",
	            ];

	            $column[] = [
	            'no'                => "",
	            'Referrer'          => "",
	            'Referrer Username' => "",
	            'Referee'           => "",
	            'Referee Username'  => "",
	            'comm_earn'         => "",
	            'amount'            => "",
	            'com_rate'          => "Grand Total",
	            'commission'        => '$ '.number_format($finalTotal,2),
	            ];
        }

        $result = myCustome::Excel($column,'Available Commissions','xlsx');
	}

	public function userLevelCommision(Request $request)
	{
		$dateRange = "";
		$commType = "available";
		$stdt = "";
		$endt = "";
		$perPage = config('services.DATATABLE.PERPAGE');
		$user    = Auth::user();
		if($request->startdt != null && $request->enddt != null)
		{
			$mode 		= 'search';
			$startdt  	= Carbon::parse($request->startdt)->format('m-d-Y');
			$startdt 	= Carbon::createFromFormat('m-d-Y', $startdt);
			$enddt  	= Carbon::parse($request->enddt)->format('m-d-Y');
			$enddt 		= Carbon::createFromFormat('m-d-Y', $enddt);

			$stdt = $request->startdt;
			$endt = $request->enddt;

			$levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.downlineid')
			->leftjoin('referral', 'referral.userid', '=', 'level_commisions.downlineid')
			->whereDate('level_commisions.created_at', '>=', $startdt->toDateString())
	        ->whereDate('level_commisions.created_at', '<=', $enddt->toDateString())
			->where('level_commisions.created_by',$user->id)
			->orderby('level_commisions.created_at','asc')
			->select('level_commisions.*','users.first_name','users.last_name','users.username','referral.refid as refernceid')
			->paginate($perPage);

			$totalAmt = LevelCommision::where('level_commisions.created_by',$user->id)
			->whereDate('created_at', '>=', $startdt->toDateString())
	        ->whereDate('created_at', '<=', $enddt->toDateString())
			->sum('commission');

	        $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';
			$levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.downlineid')
			->leftjoin('referral', 'referral.userid', '=', 'level_commisions.downlineid')
	        ->where('level_commisions.created_by',$user->id)
	        ->select('level_commisions.*','users.first_name','users.last_name','users.username','referral.refid as refernceid')
	        ->latest()
	        ->paginate($perPage);

	        $totalAmt = LevelCommision::where('level_commisions.created_by',$user->id)->sum('commission');
		}

		return view('users.pages.level-commision', compact('levelCommisions','dateRange','mode','stdt','endt','totalAmt','commType'));
	}

	public function pending()
	{
		$deposits 	= Deposit::getActiveDeposit();
		$result 	= ReDeposit::getPendingCommission($deposits);
		return view('admin.pages.level-commision-pending', compact('result'));
	}

	public function approveLevelCommision(Request $request)
	{
		$id = $request->eid;
		$data = LevelCommision::findOrFail($id);

		$update = $data->update([
				'status' 			=> 'approved',
				'modified_by'		=> Auth::user()->id,
			]);

		$sendArray  = array(
						'notifiable_id' 	=> $data->created_by,
						'link_id' 			=> $data->comid,
						'type' 				=> 'level_commision_approve',
						'commision' 		=> $data->commission,
						'com_rate' 			=> $data->com_rate,
						'com_amount' 		=> $data->com_amount,
		);

		Notifications::Notify($sendArray);

		if($update)
		{
			Session::flash('message', 'Success! Level commission approved. ');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/level-commision');
	}

	public function viewRecord($id = null)
	{
		$levelCommision = LevelCommision::join('users', 'users.id', '=', 'level_commisions.downlineid')
		->leftjoin('referral', 'referral.userid', '=', 'level_commisions.downlineid')
		->where('level_commisions.comid' ,$id)
        ->select('level_commisions.*','users.first_name','users.last_name','users.username','referral.refid as refernceid')
        ->first();
        $mode = 'View';
        Notifications::readNotification('level-commission',$id);
        Notifications::readNotification('level_commision_approve',$id);
        if(count($levelCommision) > 0)
        {
        	return view('users.pages.new-level-commision', compact('levelCommision','mode'));
        }
        else
        {
        	abort(404);
        }
	}

	public function report(Request $request)
	{
		$user = User::where('id',$request->id)->select('username','first_name','last_name')->first();
		if(count($user) > 0)
		{
			$name = $user->first_name.' '.$user->last_name.' | '.$user->username;
			$balance = myCustome::getBalance($request->id,'user',$request->id);
			$balance['pending_commission_list'] = "";
			echo json_encode(array('msg' => 'success' ,'name' => $name,'data' => $balance ));
		}
	}

	public function userPendingCommission(Request $request)
	{

		ini_set('max_execution_time', 800);
        ini_set('memory_limit','1024M');
        
		$id 					= Auth::user()->id;
		$type = '';
		if(!Auth::user()->hasRole('user'))
		{
			$type = 'all';
		}

		$balance 				= myCustome::getBalance($id,$type);
		$allRecord2 			= $balance['pending_commission_list'];
		$available_commission   = $balance['available_commission'];
        $pending_commission     = $balance['pending_commission'];
        $withdraw_commission    = $balance['withdraw_commission'];
        $wallet_commission    	= $balance['wallet_commission'];
		$html 					= "";

		if(Auth::user()->hasRole('user'))
		{
			$html  .= '<table class="table table-striped b-t" ui-jp="dataTable" ui-options="{ bSort: false, pageLength: '.config('services.DATATABLE.PERPAGE').' }">'
				.'<thead>'
					.'<tr>'
						.'<th>#</th>'
						.'<th>Username</th>'
						.'<th>Name</th>'
						.'<th>Referral Level</th>'
						.'<th>Status</th>'
						.'<th>Commission Due Date</th>'
						.'<th>Commission Paid %</th>'
						.'<th>Commission ($)</th>'
					.'</tr>'
				.'</thead>'
				.'<tbody>';
					$a = 1;
					if(count($allRecord2) > 0)
					{
						foreach($allRecord2 as $singleRecord)
						{
							$html .='<tr>'
							.'<th scope="row">'.$a.'</th>'
							.'<td>'.$singleRecord['username'].'</td>'
							.'<td>';
							if(Auth::user()->id == $singleRecord['parentUpline_id'])
							{
								$html .= ucwords($singleRecord['name']);
							}
							else
							{
								$html .= '-';
							}
							$html .= '</td>'
							.'<td>';
							$html .= myCustome::addOrdinalNumberSuffix($singleRecord['level']);

							$html .='Level</td>'
							.'<td nowrap="nowrap">'
								.'<span class="text-danger" title="Pending"><i class="material-icons">&#xE88F;</i> Pending </span>'
							.'</td>'
							.'<td title="'.dispayTimeStamp($singleRecord['endDate'])->diffForHumans().'" nowrap="nowrap">'.dispayTimeStamp($singleRecord['endDate'])->toDayDateTimeString().'</td>'
							.'<td nowrap="nowrap">'.number_format($singleRecord['commission_rate'],2).' %</td>'
							.'<td nowrap="nowrap">$ '.number_format($singleRecord['commission'],2).'</td>'
						.'</tr>';

						$a++;
					}
				}
				else
				{
					$html .= '<tr><td class="text-center" colspan="8">No Records !</td></tr>';
				}
				$html .= '</tbody>'
				.'<tfoot>'
					.'<tr>'
						.'<th colspan="7" class="text-right no-border"><strong>Total</strong></th>'
						.'<th nowrap="nowrap"><strong>$ '.number_format($pending_commission,2).'</strong></th>'
					.'</tr>'
				.'</tfoot>'
			.'</table>';
		}
		
		echo json_encode(array('msg' => 'success','data' => $html , 'available_commission' => $available_commission,'pending_commission' => $pending_commission,'withdraw_commission' => $withdraw_commission,'wallet_commission' => $wallet_commission));
	}
	
	public function exportPending(Request $request)
	{
		$deposits 	= Deposit::getActiveDeposit();
		$result 	= ReDeposit::getPendingCommission($deposits);

		$allRecord = $result['list'];
		$cnt = count($allRecord);
		if($cnt >0)
		{
			$date = array();
			for($i=0;$i<$cnt;$i++)
			{
				$date[$i] 	= $allRecord[$i]['upline_username'];
			}
			array_multisort($date, SORT_ASC, $allRecord);
			$allRecord  = $allRecord;

			$column[] 	= ['No', 'Referrer','Referrer Username','Referee','Referee Username','Commission Due Date','Amount ($)','Commission %','Commission Earn ($)'];

			$a = 1;
	        $temp = "";
	        $total = 0;
	        $finalTotal = 0;
	        for($i=0;$i<$cnt;$i++)
	        {
	            if($temp == "")
	            {
	            	$temp = $allRecord[$i]['upline_username'];
	            }

	            if($temp == $allRecord[$i]['upline_username'])
	            {
	            	$column[] = [
		            'no'                => $a,
		            'Referrer'          => $allRecord[$i]['upline_name'],
		            'Referrer Username' => $allRecord[$i]['upline_username'],
		            'Referee'           => $allRecord[$i]['name'],
		            'Referee Username'  => $allRecord[$i]['username'],
		            'comm_earn'         => $allRecord[$i]['endDate']->toDayDateTimeString(),
		            'amount'            => '$ '.number_format($allRecord[$i]['amount'],2),
		            'com_rate'          => number_format($allRecord[$i]['commission_rate'],2).' %',
		            'commission'        => '$ '.number_format($allRecord[$i]['commission'],2),
		            ];

		            $total = $allRecord[$i]['commission'] + $total;

		            $finalTotal = $allRecord[$i]['commission'] + $finalTotal;
	            }
	            else
	            {
					$column[] = [
		            'no'                => "",
		            'Referrer'          => "",
		            'Referrer Username' => "",
		            'Referee'           => "",
		            'Referee Username'  => "",
		            'comm_earn'         => "",
		            'amount'            => "",
		            'com_rate'          => "Total",
		            'commission'        => '$ '.number_format($total,2),
		            ];

		            $temp = $allRecord[$i]['upline_username'];
		            $total = 0;

		            $column[] = [
		            'no'                => $a,
		            'Referrer'          => $allRecord[$i]['upline_name'],
		            'Referrer Username' => $allRecord[$i]['upline_username'],
		            'Referee'           => $allRecord[$i]['name'],
		            'Referee Username'  => $allRecord[$i]['username'],
		            'comm_earn'         => $allRecord[$i]['endDate']->toDayDateTimeString(),
		            'amount'            => '$ '.number_format($allRecord[$i]['amount'],2),
		            'com_rate'          => number_format($allRecord[$i]['commission_rate'],2).' %',
		            'commission'        => '$ '.number_format($allRecord[$i]['commission'],2),
		            ];

		            $total = $allRecord[$i]['commission'] + $total;

		            $finalTotal = $allRecord[$i]['commission'] + $finalTotal;
	            }
	            $a++;
	        }

	        if($cnt > 0)
	        {
	        	$column[] = [
		            'no'                => "",
		            'Referrer'          => "",
		            'Referrer Username' => "",
		            'Referee'           => "",
		            'Referee Username'  => "",
		            'comm_earn'         => "",
		            'amount'            => "",
		            'com_rate'          => "Total",
		            'commission'        => '$ '.number_format($total,2),
		            ];

		            $column[] = [
		            'no'                => "",
		            'Referrer'          => "",
		            'Referrer Username' => "",
		            'Referee'           => "",
		            'Referee Username'  => "",
		            'comm_earn'         => "",
		            'amount'            => "",
		            'com_rate'          => "",
		            'commission'        => "",
		            ];

		            $column[] = [
		            'no'                => "",
		            'Referrer'          => "",
		            'Referrer Username' => "",
		            'Referee'           => "",
		            'Referee Username'  => "",
		            'comm_earn'         => "",
		            'amount'            => "",
		            'com_rate'          => "Grand Total",
		            'commission'        => '$ '.number_format($finalTotal,2),
		            ];
	        }
		}

		$result = myCustome::Excel($column,'Pending Commissions','xlsx');
	}
}
