<?php

namespace App\Http\Controllers;

use App\Models\CoreClient;
use App\Models\CoreProduct;
use App\Models\ProductType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DataTables\CoreProduct\ProductDataTable;
use App\DataTables\CoreProduct\CoreClientDataTable;
use App\Helpers\Configuration;

class CoreProductController extends Controller
{
    public function index(ProductDataTable $table) {
        Session::forget('product-token');
        Session::forget('product-data');
        Session::forget('product-addon-data');
        return $table->render('content.CoreProduct.List.index');
    }
    public function add()
    {
        if(empty(Session::get('product-token'))){
            Session::put('product-token',Str::uuid());
        }
        $pymentType = Configuration::productPaymentType();
        $sessiondata = Session::get('product-data');
        $type = ProductType::get()->pluck('name','product_type_id');
        $addons = Session::get('product-addon-data')??[];
        return view('content.CoreProduct.Add.index', compact('type', 'sessiondata','addons','pymentType'));
    }
    public function edit($product_id)
    {
        $sessiondata = Session::get('product-data');
        $type = ProductType::get()->pluck('name','product_type_id');
        $pymentType = Configuration::productPaymentType();
        $addons = Session::get('product-addon-data');
        $data = CoreProduct::with('client','addons')->find($product_id);
        if(empty($addons)){
            foreach($data->addons as $val){
                $dataadon = collect()->put('name', $val->name);
                $addon = collect(Session::get('product-addon-data'));
                $dataadon->put('date', $val->date);
                $dataadon->put('amount', $val->amount);
                $dataadon->put('amount_view', number_format($val->amount,2));
                $dataadon->put('remark', $val->remark);
                $addon->put($val->product_addon_id, $dataadon);
                Session::put('product-addon-data', $addon->toArray());
            }
        }
        return view('content.CoreProduct.Edit.index', compact('sessiondata','data','type','pymentType','addons'));
    }
    public function elemenAdd(Request $request)
    {
        $data = Session::get('product-data');
        $data[$request->name] = $request->value;
        Session::put('product-data', $data);
        return response(1);
    }
    public function processAdd(Request $request)
    {
        if(empty(Session::get('product-token'))){
            return redirect()->route('product.index')->with(['pesan' => 'Produk berhasil dimasukan*', 'alert' => 'success']);
        }
        $addon = Session::get('product-addon-data');
        try {
            DB::beginTransaction();
            $product = CoreProduct::create([
                'name'=>$request->name,
                'client_id'=>$request->client_id,
                'remark'=>$request->product_remark,
                'product_type_id'=>$request->product_type_id,
                'payment_period'=>$request->payment_period,
                'start_dev_date'=>$request->start_date,
                'trial_date'=>$request->trial_date,
                'usage_date'=>$request->usage_date,
                'maintenance_date'=>$request->maintenance_date,
                'maintenance_price'=>$request->maintenance_price,
                'payment_type'=>$request->payment_type,
            ]);
            foreach($request->termin as $key => $value){
                $product->termin()->create([
                    'order' => $key,
                    'amount'=> $value['amount']
                ]);
            }
            if(!empty($addon)){
                foreach($addon as $val){
                    $product->addons()->create([
                        'name' => $val['name'],
                        'date' => $val['date'],
                        'amount' => $val['amount'],
                        'remark' => $val['remark']
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('product.index')->with(['pesan' => 'Produk berhasil dimasukan', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('product.index')->with(['pesan' => 'Produk gagal dimasukan', 'alert' => 'danger']);
        }
    }
    public function processEdit(Request $request) {
         try {
         DB::beginTransaction();
         $cp=CoreProduct::find($request->product_id);
         $cp->name      =$request->name;
         $cp->code      =$request->code;
         $cp->account_id=$request->account_id;
         $cp->save();
         DB::commit();
         return redirect()->route('product.index')->with(['pesan' => 'Produk berhasil diedit', 'alert' => 'success']);
         } catch (\Exception $e) {
         DB::rollBack();
         report($e);
         return redirect()->route('product.index')->with(['pesan' => 'Produk berhasil diedit', 'alert' => 'danger']);
         }
    }
    public function delete($product_id) {
        try {
            DB::beginTransaction();
            CoreProduct::find($product_id)->delete();
            DB::commit();
            return redirect()->route('product.index')->with(['pesan' => 'Produk berhasil Dihapus', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('product.index')->with(['pesan' => 'Produk gagal Dihapus', 'alert' => 'danger']);
        }
    }
    public function modalClient(CoreClientDataTable $dataTable)
    {
        return $dataTable->render('content.CoreProduct.ClientModal.index');
    }
    public function selectClient($client_id)
    {
        $cl = CoreClient::find($client_id);
        $data = Session::get('product-data');
        $data['client_name'] = $cl->name;
        $data['client_id'] = $client_id;
        Session::put('product-data', $data);
        return redirect()->back();
    }
    public function addAddon(Request $request)
    {
        $id = Str::uuid()->toString();
        $data = collect()->put('name', $request->name);
        $addon = collect(Session::get('product-addon-data'));
        $data->put('date', $request->date);
        $data->put('amount', $request->amount);
        $data->put('amount_view', $request->amount_view);
        $data->put('remark', $request->remark);
        $addon->put($id, $data);
        Session::put('product-addon-data', $addon->toArray());
        $table = "<tr class='product-addon' id='pa-{$id}' data-id='{$id}'>
        <td>" . ($request->length + 1) . "</td>
        <td>{$request->date}</td>
        <td>{$request->name}</td>
        <td>Rp.{$request->amount_view}</td>
        <td>{$request->remark}</td>
        <td class='text-center'><button class='btn btn-sm btn-danger' onclick='deleteaddon('{$id}')' type='button'>Hapus</button></td>
        <tr>";
        return response($table);
    }
    public function deleteAddon(Request $request)
    {
        try {
            $addon = collect(Session::get('product-addon-data'));
            Session::put('product-addon-data', $addon->forget($request->key)->toArray());
            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e]);
        }
    }
}
