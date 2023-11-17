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
        Session::forget('client-data');
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
    public function detail($client_id) {
        $sessiondata = Session::get('client-data');
        $data = CoreClient::find($client_id);
        return view('content.CoreClient.Detail.index',compact('sessiondata','data'));
    }
    public function edit($client_id) {
        $sessiondata = Session::get('client-data');
        $data = CoreClient::find($client_id);
        return view('content.CoreClient.Edit.index',compact('sessiondata','data'));
    }
    public function processEdit(Request $request,$client_id) {
        $sessiondata = Session::get('client-data');
        $data = CoreClient::find($client_id);
        try {
            DB::beginTransaction();
                $data->name          =$request->name;
                $data->address       =$request->address;
                $data->contact_person=$request->contact_person;
                $data->phone         =$request->phone;
                $data->email         =$request->email;
                $data->save();
            DB::commit();
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil diedit','alert' => 'success']);
            } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            dd($e);
            return redirect()->route('client.index')->with(['pesan' => 'Client gagal diedit','alert' => 'error']);
            }
    }
    public function delete($client_id) {
        try {
            DB::beginTransaction();
            CoreClient::find($client_id)->delete();
            DB::commit();
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dihapus','alert' => 'success']);
            } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('client.index')->with(['pesan' => 'Client gagal dihapus','alert' => 'error']);
            }
    }
    
   public function elemenAdd(Request $request) {
   $data=Session::get('client-data');
   $data[$request->name]=$request->value;
   Session::put('client-data',$data);
   return response(1);
   }
}
