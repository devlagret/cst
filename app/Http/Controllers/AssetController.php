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
 

    // public function listAdd(Request $request)
    // {
    //     $data = AssetMenu::all(); 
    //     return datatables()->of($data)->toJson();
    // }
}

