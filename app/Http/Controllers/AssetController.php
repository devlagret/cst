<?php

namespace App\Http\Controllers;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\DataTables\PreferenceAsset\AssetDataTable;
use App\DataTables\InvoiceProduct\InvoiceDataTable;
use App\Models\AssetMenu;

class AssetController extends Controller
{
    
    public function index(AssetDataTable $table)
    {
        Session::forget("asset-data");
        return $table->render('content.PreferenceAsset.List.index');
    }
 
    public function add()
    {
        $asset_data = AssetMenu::select('asset_id', 'name','buy_date','price','acquisition_amount','estimated_age','residual_amount','remark')
        ->get();

        return view('content.PreferenceAsset.Add.index', compact('asset_data'));
    }

    public function processAdd(Request $request)
{
    $fields = $request->validate([
        'name'               => ['required'],
        'price'              => ['required', 'numeric'],
        'acquisition_amount' => ['required', 'numeric'],
        'estimated_age'      => ['required', 'numeric'],
        'residual_amount'    => ['required', 'numeric'],
        'remark'             => ['required'],
    ]);

    try {
        $add_asset = [
            'name'               => $fields['name'],
            'price'              => $fields['price'],
            'acquisition_amount' => $fields['acquisition_amount'],
            'estimated_age'      => $fields['estimated_age'],
            'residual_amount'    => $fields['residual_amount'],
            'remark'             => $fields['remark'],
            'created_id'         => auth()->user()->user_id
        ];

        AssetMenu::create($add_asset);

        return redirect()->route('as-report.index')->with([
            'pesan' => 'Data asset berhasil ditambah',
            'alert' => 'success'
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with([
            'pesan' => 'Gagal menambahkan data asset: ' . $e->getMessage(),
            'alert' => 'error'
        ]);
    }
}
public function edit($id)
{
    $add_asset = AssetMenu::select('asset_id', 'name','buy_date','price','acquisition_amount','estimated_age','residual_amount','remark')
    ->where('asset_id', $id)
    ->first();
    $asset_add = AssetMenu::select('asset_id')
    ->get();

    return view('content.PreferenceAsset.Edit.index', compact('add_asset','asset_add'));
}

    public function processEdit(Request $request)
    {
        $table                     = AssetMenu::findOrFail($request->asset_id);
        $table->name               = $request->name;
        $table->price              = $request->price;
        $table->acquisition_amount = $request->acquisition_amount;
        $table->estimated_age      = $request->estimated_age;
        $table->residual_amount    = $request->residual_amount;
        $table->remark             = $request->remark;
        $table->updated_id         = auth()->user()->user_id;

        if ($table->save()) {
            $message = array(
                'pesan' => 'Data berhasil diubah',
                'alert' => 'success'
            );
        } else {
            $message = array(
                'pesan' => 'Data gagal diubah',
                'alert' => 'error'
            );
        }

        return redirect('asset')->with($message);
    }
    public function delete(Request $request, $asset_id)
    {
        $asset = AssetMenu::findOrFail($asset_id);
    
        if($asset->delete()) {
            $message = [
                'pesan' => 'Data berhasil dihapus',
                'alert' => 'success'
            ];
        } else {
            $message = [
                'pesan' => 'Data gagal dihapus',
                'alert' => 'error'
            ];
        }
    
        return redirect('asset')->with($message);
    }
}

