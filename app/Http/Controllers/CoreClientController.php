<?php

namespace App\Http\Controllers;

use App\Models\CoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\CoreClientDataTable;
use Illuminate\Support\Facades\Session;

class CoreClientController extends Controller
{
    public function index(CoreClientDataTable $table) {
        return $table->render('content.CoreClient.List.index');
    }
    public function add() {
        $sessiondata = Session::get('client-data');
        return view('content.CoreClient.Add.index',compact('sessiondata'));
    }
    public function processAdd(Request $request) {
        $sessiondata = Session::get('client-data');
        try {
        DB::beginTransaction();
        CoreClient::create([
            'name'=>$request->name,
            'address'=>$request->address,
            'contact_person'=>$request->contact_person,
            'phone'=>$request->phone,
            'email'=>$request->email,
        ]);
        DB::commit();
        return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dimasukan','alert' => 'success']);
        } catch (\Exception $e) {
        DB::rollBack();
        report($e);
        dd($e);
        return redirect()->route('client.index')->with(['pesan' => 'Client gagal dimasukan','alert' => 'error']);
        }
    }
   public function elemenAdd(Request $request) {
   $data=Session::get('client-data');
   $data[$request->name]=$request->value;
   Session::put('client-data',$data);
   return response(1);
   }
}
