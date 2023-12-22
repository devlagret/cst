<?php
namespace App\Http\Controllers;
use App\Models\AcctAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DataTables\ProductTypeDataTable;
use App\Models\ProductType;
class ProductTypeController extends Controller
{
    public function index(ProductTypeDataTable $table)
    {
        Session::forget('product-type');
        return $table->render("content.ProductType.List.index");
    }
    public function add()
    {
        $sessiondata = Session::get('product-type');
        $account = AcctAccount::select('account_id', DB::raw('CONCAT(account_code, " - ", account_name) as name'))->get()->pluck('name', 'account_id');
        return view('content.ProductType.Add.index', compact('account', 'sessiondata'));
    }
    public function edit($product_type_id)
    {
        collect()->toArray();
        $sessiondata = Session::get('product-type');
        $data = ProductType::find($product_type_id);
        $account = AcctAccount::select('account_id', DB::raw('CONCAT(account_code, " - ", account_name) as name'))->get()->pluck('name', 'account_id');
        return view('content.ProductType.Edit.index', compact('sessiondata','data','account'));
    }
    public function elemenAdd(Request $request)
    {
        $data = Session::get('product-type');
        $data[$request->name] = $request->value;
        Session::put('product-type', $data);
        return response(1);
    }
    public function processAdd(Request $request)
    {
        try {
            DB::beginTransaction();
            ProductType::create([
                'name'=>$request->name,
                'code'=>$request->code,
                'account_id'=>$request->account_id,
            ]);
            DB::commit();
            return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk berhasil dimasukan', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk gagal dimasukan', 'alert' => 'danger']);
        }
    }
    public function processEdit(Request $request) {
         try {
         DB::beginTransaction();
         $pt=ProductType::find($request->product_type_id);
         $pt->name      =$request->name;
         $pt->code      =$request->code;
         $pt->account_id=$request->account_id;
         $pt->save();
         DB::commit();
         return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk berhasil diedit', 'alert' => 'success']);
         } catch (\Exception $e) {
         DB::rollBack();
         report($e);
         return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk berhasil diedit', 'alert' => 'danger']);
         }
    }
    public function delete($product_type_id) {
        try {
            DB::beginTransaction();
            ProductType::find($product_type_id)->delete();
            DB::commit();
            return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk berhasil Dihapus', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('product-type.index')->with(['pesan' => 'Tipe produk gagal Dihapus', 'alert' => 'danger']);
        }
    }
}
