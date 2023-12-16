<?php

namespace App\Http\Controllers;

use App\Models\AcctAccount;
use Illuminate\Http\Request;
use App\Helpers\Configuration;
use App\Models\AcctAccountSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AcctAccountSettingController extends Controller
{
    public function index() {
        $account = AcctAccount::select('account_id', DB::raw('CONCAT(account_code, " - ", account_name) as name'))->get()->pluck('name', 'account_id');
        $status = Configuration::AccountStatus();
        $data = AcctAccountSetting::get();
        return view('content.AcctAccountSetting.index',compact('account','status','data'));
    }
    public function processAddAcctAccountSetting(Request $request)
    {
        // dd($request->all());
        $data = collect([
            [
            'account_id'               => $request->purchase_cash_account_id,
            'account_setting_status'   => $request->purchase_cash_account_status,
            'account_setting_name'     => 'purchase_account',
            ],
        ]);
        foreach($data as $val){
            AcctAccountSetting::updateOrCreate([
                'account_setting_name'  => $val["account_setting_name"],
                'branch_id'            => Auth::user()->branch_id
            ],[
                'account_id' 			  => $val["account_id"],
                'account_setting_status'  => $val["account_setting_status"],
                'account_default_status'  => $this->getAccountDefault($val["account_id"]),
            ]);
        }
        return redirect()->back()->with(['pesan' => 'Pengaturan Akun berhasil disimpan', 'alert' => 'success']);
    }
    public function getAccountDefault($account_id)
    {
        $data = AcctAccount::where('account_id', $account_id)->first();

        return $data['account_default_status'] ?? '';
    }
}
