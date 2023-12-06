<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CoreBranch;
use Illuminate\Http\Request;
use App\Models\AcctJournalVoucher;

class JournalMemorialController extends Controller
{
    public function index()
    {
        $session = session()->get('filter_journalmemorial');
        $corebranch = CoreBranch::select('branch_id', 'branch_name')->get();
        $acctmemorialjournal = AcctJournalVoucher::with('items.account')->where('journal_voucher_status',1)
        ->where('journal_voucher_date','>=', $session['start_date']??Carbon::now()->format('Y-m-d'))
        ->where('journal_voucher_date','<=', $session['end_date']??Carbon::now()->format('Y-m-d'));
        if(!empty($session['branch_id'])) {
            $acctmemorialjournal = $acctmemorialjournal->where('branch_id', $session['branch_id']);
        }
        $acctmemorialjournal = $acctmemorialjournal->orderByDesc('created_at')->get();

        return view('content.JournalMemorial.List.index',compact('corebranch','session','acctmemorialjournal'));
    }

    public function filter(Request $request)
    {
        if($request->start_date){
            $start_date = $request->start_date;
        }else{
            $start_date = date('d-m-Y');
        }
        if($request->end_date){
            $end_date = $request->end_date;
        }else{
            $end_date = date('d-m-Y');
        }

        $sessiondata = array(
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'branch_id'     => $request->branch_id,
        );

        session()->put('filter_journalmemorial', $sessiondata);

        return redirect('journal-memorial');
    }

    public function resetFilter()
    {
        session()->forget('filter_journalmemorial');

        return redirect('journal-memorial');
    }
}
