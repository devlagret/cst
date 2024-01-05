<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use NumberFormatter;
use App\Models\AcctInvoice;
use App\Models\CoreProduct;
use Illuminate\Support\Str;
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
use App\Helpers\AppHelper;

class InvoiceController extends Controller
{
    public function index(InvoiceDataTable $table)
    {
        Session::forget('invoice-data');
        $paymentType = AppHelper::paymentType();
        return $table->with(['paymentType' => $paymentType])->render('content.InvoiceProduct.List.index', compact('paymentType'));
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
        $data = CoreProduct::with(['client', 'termin' => function ($q) {
            $q->where('payment_status', 0);
        }, 'type', 'addons' => function ($q) {
            $q->where('payment_status', 0);
        }])
            ->find($product_id);
        return view('content.InvoiceProduct.Add.add', compact('sessiondata', 'data', 'pType'));
    }
    public function maintenance($product_id)
    {
        $sessiondata = Session::get('invoice-data');
        $pType = Configuration::productPaymentType();
        $invdate = AppHelper::getMaintenaceDate($product_id);
        $bln = Configuration::Month();
        $year = Configuration::years();
        $now = Carbon::now();
        // dd($year);
        $data = CoreProduct::with(['client', 'termin' => function ($q) {
            $q->where('payment_status', 0);
        }, 'type', 'addons' => function ($q) {
            $q->where('payment_status', 0);
        }])
            ->find($product_id);
        return view('content.InvoiceProduct.Add.addMaintenance', compact('sessiondata', 'data', 'pType', 'bln', 'year'));
    }
    public function processMaintenance(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $invoice = AcctInvoice::create([
                'invoice_date' => Carbon::now()->format('Y-m-d'),
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'subtotal_amount' => $request->sbs_amount,
                'total_amount' => $request->total_amount,
                'discount_percentage' => $request->discount_percentage,
                'discount_amount' => $request->discount_amount,
                'adjustment_amount' => $request->adjustmen,
                'tax_ppn_amount' => $request->ppn_amount,
                'tax_ppn_percentage' => $request->ppn_percentage,
                'remark' => $request->remark,
                'maintenance_date' => ("{$request->year}-{$request->month}-01"),
                'invoice_type' => 3
            ]);

            $invoice->items()->create([
                'subtotal_amount' =>  $request->sbs_amount,
                'total_amount' => $request->total_amount,
                'remark' => "Maintenance",
                'invoice_type' => 3
            ]);
            DB::commit();
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice berhasil dibuat', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice gagal dibuat', 'alert' => 'danger']);
        }
    }
    public function processAdd(Request $request)
    {
        $preference = CompanySetting::where('company_id', Auth::user()->company_id)->get();
        $product = CoreProduct::with('type')->find($request->product_id);
        try {
            DB::beginTransaction();
            $invoice = AcctInvoice::create([
                'invoice_date' => Carbon::now()->format('Y-m-d'),
                'client_id' => $request->client_id,
                'product_id' => $request->product_id,
                'subtotal_amount' => $request->sbs_amount,
                'total_amount' => $request->total_amount,
                'discount_percentage' => $request->discount_percentage,
                'discount_amount' => $request->discount_amount,
                'adjustment_amount' => $request->adjustmen,
                'tax_ppn_amount' => $request->ppn_amount,
                'tax_ppn_percentage' => $request->ppn_percentage,
                'remark' => $request->remark,
            ]);
            $totaltermin = 0;
            $totaladd = 0;
            foreach ($request->addon as $key => $val) {
                if (count($val) == 2) {
                    $addon = CoreProductAddon::find($val['id']);
                    $invoice->items()->create([
                        'subtotal_amount' => $val['amount'],
                        'total_amount' => $val['amount'],
                        'item_id' => $val['id'],
                        'remark' => $addon->remark,
                        'invoice_type' => 1
                    ]);
                    $totaladd += $val['amount'];
                    $addon->payment_status = 1;
                    $addon->save();
                }
            }
            foreach ($request->termin as $key => $value) {
                if (count($value) == 2) {
                    $invoice->items()->create([
                        'subtotal_amount' => $value['amount'],
                        'total_amount' => $value['amount'],
                        'item_id' => $value['id'],
                        'remark' => ($value['remark'] ?? "Pembayaran Termin"),
                        'invoice_type' => 2
                    ]);
                    $totaltermin += $value['amount'];
                    $termin = CoreProductTermin::find($value['id']);
                    $termin->payment_status = 1;
                    $termin->save();
                }
            }
            $invoicenew = $invoice->fresh();
            // if ($totaltermin) {
            //     $journalter = JournalHelper::code('SI')->clientId($request->client_id)->trsJournalId($invoicenew->invoice_id)->trsJournalNo($invoicenew->invoice_no)->appendTitle("Termin {$request->name} {$request->client_name}", 1)->make('Invoice', $totaladd);
            //     $journalter->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 0);
            //     $journalter->item($product->type()->pluck('account_id')[0], 1);
            // }
            // if ($totaladd) {
            //     $journaladd = JournalHelper::code('SI')->clientId($request->client_id)->trsJournalId($invoicenew->invoice_id)->trsJournalNo($invoicenew->invoice_no)->appendTitle("Addon {$request->name} {$request->client_name}", 1)->make('Invoice', $totaladd);
            //     $journaladd->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 0);
            //     $journaladd->item($product->type()->pluck('account_id')[0], 1);
            // }
            $cp = CoreProduct::with(['client', 'termin' => function ($q) {
                $q->where('payment_status', 0);
            }, 'type', 'addons' => function ($q) {
                $q->where('payment_status', 0);
            }])->find($request->product_id);
            if (!$cp->termin()->exists() && !$cp->type()->exists()) {
                $cp->payment_status = 1;
                $cp->save();
            }
            DB::commit();
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice berhasil dibuat', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice gagal dibuat', 'alert' => 'danger']);
        }
    }
    public function print($invoice_id)
    {
        $preference = CompanySetting::where('company_id', Auth::user()->company_id)->get();
        $invoice = AcctInvoice::with('product', 'client', 'items')->find($invoice_id);
        if ($invoice->invoice_status == 0) {
            try {
                DB::beginTransaction();
                if ($invoice->invoice_type == 3) {
                    $journalter = JournalHelper::code('SI')->clientId($invoice->client_id)->trsJournalId($invoice->invoice_id)->trsJournalNo($invoice->invoice_no)->appendTitle("Maintenance {$invoice->name} {$invoice->client->client_name}", 1)
                        ->make('Invoice', ($invoice->items()->where('invoice_type', 3)->sum('total_amount')));
                    $journalter->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 0);
                    $journalter->item($preference->where('name', 'maintenance_account')->pluck('account_id')[0]);
                } else {
                    $product = CoreProduct::with('type')->find($invoice->product_id);
                    $totaltermin =  $invoice->items()->where('invoice_type', 1)->sum('total_amount');
                    $totaladd = $invoice->items()->where('invoice_type', 2)->sum('total_amount');
                    if ($totaltermin) {
                        $journalter = JournalHelper::code('SI')->clientId($invoice->client_id)->trsJournalId($invoice->invoice_id)->trsJournalNo($invoice->invoice_no)->appendTitle("Termin {$invoice->name} {$invoice->client_name}", 1)->make('Invoice', $totaladd);
                        $journalter->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 0);
                        $journalter->item($product->type()->pluck('account_id')[0], 1);
                    }
                    if ($totaladd) {
                        $journaladd = JournalHelper::code('SI')->clientId($invoice->client_id)->trsJournalId($invoice->invoice_id)->trsJournalNo($invoice->invoice_no)->appendTitle("Addon {$invoice->name} {$invoice->client_name}", 1)->make('Invoice', $totaladd);
                        $journaladd->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 0);
                        $journaladd->item($product->type()->pluck('account_id')[0], 1);
                    }
                }
                $invoice->invoice_status = 1;
                $invoice->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                report($e);
                return redirect()->route('invoice.index')->with(['pesan' => 'Terjadi Kesalahan Silahkan Coba Lagi', 'alert' => 'danger']);
            }
        }
        // exit;   
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::setHeaderMargin(5);
        $header = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                        <tr>
                            <td rowspan=\"4\" width=\"60%\"><img src=\"" . asset('img/logo_nota.png') . "\" width=\"200\"></td>
                            <td width=\"40%\" style=\"text-align: center; font-weight: bold;font-size: 14px; \">CV CIPTA SOLUTINDO TECH</td>
                        </tr>
                        <tr>
                            <td width=\"40%\" style=\"text-align: center; font-weight: bold;\">AHU 005856-AH.01.14 Tahun 2021</td>
                        </tr>
                        <tr>
                            <td width=\"40%\" style=\"text-align: center; font-weight: bold;\">NPWP : 85.564.807.7-532.000</td>
                        </tr>
                        <tr>
                            <td width=\"40%\" style=\"text-align: center;\"> Jl. Raya Solo - Tawangmangu Km 8 Triyagan, Mojolaban Kab Sukoharjo</td>
                        </tr>
                    </table>";
        $pdf::setHeaderCallback(function ($pdf) use ($header) {
            $pdf->SetFont('helvetica', '', 8);
            $pdf->writeHTML($header, true, false, false, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) use ($invoice) {
            $pdf->SetFont('helvetica', '', 8);
        });
        $footer = "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
                        <tr>
                            <td width=\"60%\" style=\"font-weight: bold; border-top:1px solid black;border-left:1px solid black;border-right:1px solid black; text-decoration: underline;\">Rekening Transfer :</td>
                            <td rowspan=\"4\" style=\"text-align: center;\" width=\"50%\"><img src=\"" . asset('img/ttd_nota.png') . "\" width=\"170\"></td>
                        </tr>
                        <tr>
                            <td width=\"60%\" style=\" font-weight: bold; border-left:1px solid black;border-right:1px solid black;\">MANDIRI A/C : 138 00 1597445 9 , An : CV CIPTA SOLUTINDO TECH</td>
                        </tr>
                        <tr>
                            <td width=\"60%\" style=\" font-weight: bold; border-left:1px solid black;border-right:1px solid black;\">BRI A/C : 0512 01 003 834 536 , An : ANTONIUS IRAWAN EKO S</td>
                        </tr>
                        <tr>
                        <td width=\"60%\" style=\" font-weight: bold; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black; \">BCA  A/C : 3270847233 , An : ANTONIUS IRAWAN EKO S</td>
                        </tr>
                        <tr>
                        <td width=\"60%\" style=\"text-align: center;\"> </td>
                            <td style=\"text-align: center; font-weight: bold;font-size: 10px; text-decoration: underline; \">Admin Cipta Solutindo</td>
                        </tr>
                    </table>";
        $f = new NumberFormatter("id", NumberFormatter::SPELLOUT);
        $spelled = "<table cellspacing=\"0\" cellpadding=\"10\" border=\"0\">
                    <tr>
                        <td width=\"15%\" style=\" text-align: center; font-weight: bold; border-left:1px solid black; border-top:1px solid black;border-bottom:1px solid black;\">Terbilang :</td>
                        <td width=\"83%\" style=\" border-right:1px solid black;font-size: 14px;font-weight: bold;font-style: italic; border-top:1px solid black;border-bottom:1px solid black;\">" . Str::title($f->format($invoice->subtotal_amount)) . " Rupiah</td>
                    </tr>
                </table>";

        $pdf::SetMargins(15, 20, 15, true);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf::setLanguageArray($l);
        }
        $pdf::AddPage('P', 'A4');
        $pdf::SetFont('helvetica', '', 8);

        $title = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                        <tr>
                            <td style=\"text-align: center; font-weight: bold;font-size: 24px; \">INVOICE</td>
                        </tr>
                    </table>";
        $pdf::writeHTML("<br/><br/><br/>" . $title, true, false, false, false, '');
        $pembayaran = ($invoice->invoice_status ? Apphelper::paymentType($invoice->payment_type) : AppHelper::paymentStatus($invoice->invoice_status));
        $detail = "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
                        <tr>
                        <td width=\"10%\" style=\"border-top:1px solid black;border-left:1px solid black; solid black;\">Pelanggan</td>
                        <td width=\"38%\" style=\"border-top:1px solid black;solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . ($invoice->client->name ?? '-') . "</td>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-top:1px solid black;border-left:1px solid black; solid black;\">No Invoice</td>
                        <td width=\"20%\" style=\"border-top:1px solid black;solid black; solid black;font-weight: bold;border-right:1px solid black;\">{$invoice->invoice_no}</td>
                        </tr>
                        <tr>
                        <td width=\"10%\" rowspan=\"2\"  style=\"border-left:1px solid black; solid black;\">Alamat</td>
                        <td width=\"38%\" rowspan=\"2\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . ($invoice->client->address ?? '-') . "</td>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">Tanggal</td>
                        <td width=\"20%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . Carbon::now()->translatedFormat('d F Y') . "</td>
                        </tr>
                        <tr>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">Order No</td>
                        <td width=\"20%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">---</td>
                        </tr>
                        <tr>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">Email</td>
                        <td width=\"38%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . ($invoice->client->email ?? '-') . "</td>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">Pembayaran</td>
                        <td width=\"20%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">{$pembayaran}</td>
                        </tr>
                        <tr>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">No Hp</td>
                        <td width=\"38%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . ($invoice->client->phone ?? '-') . "</td>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;\">Status</td>
                        <td width=\"20%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;\">" . AppHelper::paymentStatus($invoice->invoice_status) . "</td>
                        </tr>
                        <tr>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black; border-bottom:1px solid black;\">Kontak</td>
                        <td width=\"38%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;border-bottom:1px solid black;\">" . ($invoice->client->contact_person ?? '-') . "</td>
                        <td width=\"20%\"></td>
                        <td width=\"10%\" style=\"border-left:1px solid black; solid black;border-bottom:1px solid black;\">Hal</td>
                        <td width=\"20%\" style=\"solid black; solid black;font-weight: bold;border-right:1px solid black;border-bottom:1px solid black;\">" . $pdf::getAliasNumPage() . " of " . $pdf::getAliasNbPages() . "</td>
                        </tr>
                    </table>";
        $pdf::writeHTML($detail . "<br/><br/><br/>", true, false, false, false, '');

        $remark = "<table cellspacing=\"0\" cellpadding=\"10\" border=\"0\">
                        <tr>
                            <td width=\"14%\" style=\"line-height: 1.2; text-align: center; font-weight: bold; border-left:1px solid black; border-top:1px solid black;border-bottom:1px solid black;\">Keterangan :</td>
                            <td width=\"84%\" style=\"line-height: 1.2;font-size: 12px; border-right:1px solid black; font-weight: bold; border-top:1px solid black;border-bottom:1px solid black;\">{$invoice->remark}</td>
                        </tr>
                    </table>";
        $pdf::writeHTML($remark . "<br/><br/><br/>", true, false, false, false, '');

        $tbl = "<table cellspacing=\"0\" cellpadding=\"9\" border=\"0\">
        <tr>
        <td width=\"7%\" style=\"text-align: center; border:1px solid black; font-weight: bold;\">No</td>
        <td width=\"45%\" style=\"text-align: center; border:1px solid black; font-weight: bold;\">Item / Deskripsi</td>
        <td width=\"10%\" style=\"text-align: center; border:1px solid black; font-weight: bold;\">Jumlah</td>
        <td width=\"18%\" style=\"text-align: center; border:1px solid black; font-weight: bold;\">Harga</td>
        <td width=\"18%\" style=\"text-align: center; border:1px solid black; font-weight: bold;\">Total</td>
        </tr>
        ";
        $no = 1;
        $diskon = "";
        $payed = "";
        if ($invoice->discount_amount != 0) {
            $diskon = "<tr>
            <td style=\"text-align: left; border:1px solid black; font-weight: bold;\">Diskon ({$invoice->discount_percentage}%)</td>
            <td style=\"text-align: right; border:1px solid black; font-weight: bold;\">" . number_format($invoice->discount_amount, 2) . "</td>
            </tr> ";
        }
        if ($invoice->invoice_status != 0) {
            $diskon = "<tr>
            <td style=\"text-align: left; border:1px solid black; font-weight: bold;\">Dibayar</td>
            <td style=\"text-align: right; border:1px solid black; font-weight: bold;\">" . number_format($invoice->payed_amount, 2) . "</td>
            </tr> ";
        }

        foreach ($invoice->items as $val) {
            $tbl .= "<tr>
            <td style=\"text-align: center; border:1px solid black;\">" . $no++ . "</td>
            <td style=\"border:1px solid black;\">{$val->remark}</td>
            <td style=\"border:1px solid black;\">1 Paket</td>
            <td style=\"text-align: rigth;border:1px solid black;\">" . number_format($val->subtotal_amount, 2) . "</td>
            <td style=\"text-align: rigth;border:1px solid black;\">" . number_format($val->total_amount, 2) . "</td>
            </tr>";
        }

        $tbl .= "
        <tr>
        <td rowspan=\"5\" colspan=\"3\" ></td>
        <td style=\"text-align: left; border:1px solid black; font-weight: bold;\">Sub Total</td>
        <td style=\"text-align: right; border:1px solid black; font-weight: bold;\">" . number_format($invoice->subtotal_amount, 2) . "</td>
        </tr>
        {$diskon}
        <tr>
        <td style=\"text-align: left; border:1px solid black; font-weight: bold;\">PPN ({$invoice->tax_ppn_percentage}%)</td>
        <td style=\"text-align: right; border:1px solid black; font-weight: bold;\">" . number_format($invoice->tax_ppn_amount, 2) . "</td>
        </tr>
        <tr>
        <td style=\"text-align: left; border:1px solid black; font-weight: bold;\">Total</td>
        <td style=\"text-align: right; border:1px solid black; font-weight: bold;\">" . number_format($invoice->total_amount, 2) . "</td>
        </tr>
        {$payed}
        </table>
        ";
        $pdf::writeHTML($tbl . "<br/><br/><br/>", true, false, false, false, '');
        $pdf::writeHTML($spelled . "<br/><br/><br/>", true, false, false, false, '');
        $pdf::writeHTML($footer, true, false, false, false, '');
        $filename = 'Nota.pdf';
        $pdf::setTitle("Nota");
        $pdf::Output($filename);
    }
    public function repaymentMaint(Request $request)
    {
        dd($request->all());
        $preference = CompanySetting::where('company_id', Auth::user()->company_id)->get();
        try {
            DB::beginTransaction();
            $invoice = AcctInvoice::find($request->invoice_id);
            $invoice->invoice_status = 2;
            $invoice->save();
            if ($invoice->invoice_type == 3) {
                $journalter = JournalHelper::code('SI')->clientId($invoice->client_id)->trsJournalId($invoice->invoice_id)->trsJournalNo($invoice->invoice_no)->appendTitle("Pembayaran Maintenance {$request->name} {$request->client_name}", 1)->make('Invoice', $request->sbs_amount);
                $journalter->item($preference->where('name', 'receivables_account')->pluck('account_id')[0], 1);
                $journalter->item($preference->where('name', 'cash_account')->pluck('account_id')[0]);
            }
            DB::rollBack();
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice berhasil dibayar', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice gagal dibayar', 'alert' => 'danger']);
        }
    }
    public function reject($invoice_id)
    {
        try {
            DB::beginTransaction();
            $invoice = AcctInvoice::with('items')->find($invoice_id);
            if ($invoice->invoice_status != 0) {
                return redirect()->route('invoice.index')->with(['pesan' => 'Invoice Tidak Bisa direject', 'alert' => 'danger']);
            }
            $invoice->items()->delete();
            $invoice->delete();
            DB::commit();
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice berhasil direject', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('invoice.index')->with(['pesan' => 'Invoice gagal direject', 'alert' => 'danger']);
        }
    }
    public function month(Request $request)
    {
        $invdate = AppHelper::getMaintenanceMonth($request->product_id, $request->year);
        $bln = Configuration::Month()->forget($invdate)->toArray();
        $select = "";
        foreach ($bln as $key => $val) {
            $select .= "<option value='{$key}'>{$val}</option>";
        }
        return response($select);
    }
}

