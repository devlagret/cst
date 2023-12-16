<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AcctInvoice;
use App\Models\CoreProduct;
use Illuminate\Http\Request;
use App\Helpers\Configuration;
use App\Helpers\JournalHelper;
use App\Models\CompanySetting;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\CoreProductAddon;
use App\Models\CoreProductTermin;
use App\Models\PreferenceCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\InvoiceProduct\InvoiceDataTable;
use App\DataTables\InvoiceProduct\ProductDataTable;

class InvoiceController extends Controller
{
    public function index(InvoiceDataTable $table)
    {
        Session::forget('invoice-data');
        return $table->render('content.InvoiceProduct.List.index');
    }
    public function listAdd(ProductDataTable $table)
    {
        Session::forget('invoice-data');
        return $table->render('content.InvoiceProduct.Add.index');
    }
    public function elemenAdd(Request $request)
    {
        $data = Session::get('invoice-data');
        $data[$request->name] = $request->value;
        Session::put('invoice-data', $data);
        return response(1);
    }
    public function add($product_id)
    {
        $sessiondata = Session::get('invoice-data');
        $pType = Configuration::productPaymentType();
        $data = CoreProduct::with(['client', 'termin'=>function($q){
            $q->where('payment_status', 0);
        }, 'type', 'addons'=>function($q){
            $q->where('payment_status', 0);
        }])
        ->find($product_id);
        return view('content.InvoiceProduct.Add.add', compact('sessiondata', 'data', 'pType'));
    }
    public function processAdd(Request $request)
    {
        $preference = CompanySetting::where('company_id',Auth::user()->company_id)->get();
        $product = CoreProduct::with('type')->find($request->product_id);
        try {
            DB::beginTransaction();
            $invoice = AcctInvoice::create([
                'invoice_date' => Carbon::now()->format('Y-m-d'),
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'subtotal_amount' => $request->sbs_amount,
                'total_amount' => $request->total_amount,
                'discount_percentage' => $request->discount_percentage
            ]);
            $totaltermin = 0;
            $totaladd = 0;
            foreach ($request->addon as $key => $val) {
                if (count($val) == 2) {
                    $invoice->items()->create([
                        'subtotal_amount'=>$val['amount'],
                        'total_amount'=>$val['amount'],
                        'item_id'=>$val['id'],
                        'invoice_type'=>1
                    ]);
                    $totaladd += $val['amount'];
                    $addon = CoreProductAddon::find($val['id']);
                    $addon->payment_status = 1;
                    $addon->save();
                }
            }
            foreach ($request->termin as $key => $value) {
                if (count($value) == 2) {
                    $invoice->items()->create([
                        'subtotal_amount'=>$value['amount'],
                        'total_amount'=>$value['amount'],
                        'item_id'=>$value['id'],
                        'invoice_type'=>2
                    ]);
                    $totaltermin += $value['amount'];
                    $termin = CoreProductTermin::find($value['id']);
                    $termin->payment_status = 1;
                    $termin->save();
                }
            }
            if($totaltermin){
               $journalter = JournalHelper::code('SI')->appendTitle("Termin {$request->name} {$request->client_name}",1)->make('Invoice',$totaladd);
               $journalter->item($preference->where('name','receivables_account')->pluck('account_id')[0],0);
               $journalter->item($product->type()->pluck('account_id')[0],1);
            }
            if($totaladd){
                $journaladd = JournalHelper::code('SI')->appendTitle("Addon {$request->name} {$request->client_name}",1)->make('Invoice',$totaladd);
                $journaladd->item($preference->where('name','receivables_account')->pluck('account_id')[0],0);
                $journaladd->item($product->type()->pluck('account_id')[0],1);
            }
            $cp=CoreProduct::with(['client', 'termin'=>function($q){
                $q->where('payment_status', 0);
            }, 'type', 'addons'=>function($q){
                $q->where('payment_status', 0);
            }])->find($request->product_id);
            if(!$cp->termin()->exists()&&!$cp->type()->exists()){
                $cp->payment_status=1;
                $cp->save();
            }
            // DB::rollBack();
            // exit();
            DB::commit();
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice berhasil dibuat', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice gagal dibuat', 'alert' => 'danger']);
        }
    }
    public function product() {
        // dd(asset('img/logo_nota.png') );
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(10, 5, 10, true); // put space of 10 on top

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }
        $pdf::AddPage();
        $pdf::SetFont('helvetica', '', 8);
        $header = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                        <tr>
                            <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('img/logo_nota.png') . "\" width=\"120\"></td>
                            <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                            <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        </tr>
                        <tr>
                            <td width=\"10%\"><div style=\"text-align: left;\">Dicetak</div></td>
                            <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                            <td width=\"12%\"><div style=\"text-align: left;\">" . ucfirst(Auth::user()->name) . "</div></td>
                        </tr>
                        <tr>
                            <td width=\"10%\"><div style=\"text-align: left;\">Tgl. Cetak</div></td>
                            <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                            <td width=\"12%\"><div style=\"text-align: left;\">" . date('d-m-Y H:i') . "</div></td>
                        </tr>
                    </table>";
        $pdf::writeHTML($header, true, false, false, false, '');

        // $pdf::writeHTML($tbl, true, false, false, false, '');
        $filename = 'Nota.pdf';
        $pdf::setTitle("Nota");
        $pdf::Output($filename, 'I');
    }
}
