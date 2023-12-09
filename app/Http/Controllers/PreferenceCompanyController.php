<?php

namespace App\Http\Controllers;

use App\Models\AcctAccount;
use Illuminate\Http\Request;
use App\Helpers\Configuration;
use App\Models\AcctAccountSetting;
use App\Models\PreferenceCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreferenceCompanyController extends Controller
{
    public function index() {
        $account = AcctAccount::select('account_id', DB::raw('CONCAT(account_code, " - ", account_name) as name'))->get()->pluck('name', 'account_id');
        $status = Configuration::AccountStatus();
        $company = PreferenceCompany::where('company_id',Auth::user()->company_id)->first();
        $data = $company->settings()->get();
        return view('content.PreferenceCompany.index',compact('account','status','data','company'));
    }
    public function processAdd(Request $request) {
        $company = PreferenceCompany::where('company_id',Auth::user()->company_id)->first();
        try {
        DB::beginTransaction();
        $company->company_name=$request->company['company_name'];
        $company->company_address=$request->company['company_address'];
        $company->save();
        foreach($request->account as $key => $val){
            $company->settings()->updateOrCreate(['name' => $key],['account_id'=>$val]);
        }
        DB::commit();
        return redirect()->back()->with(['pesan' => 'Pengaturan Perusanaan berhasil disimpan', 'alert' => 'success']);
        } catch (\Exception $e) {
        DB::rollBack();
        report($e);
        return redirect()->back()->with(['pesan' => 'Pengaturan Perusanaan gagal disimpan', 'alert' => 'success']);
        }
    }
}
