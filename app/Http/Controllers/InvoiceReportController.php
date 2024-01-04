<?php

namespace App\Http\Controllers;

use DateTime;
use App\Helpers\AppHelper;
use App\Helpers\Configuration;
use App\Models\CoreClient;
use App\Models\AcctInvoice;
use App\Models\CoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AcctInvoiceItem;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\PreferenceCompany;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InvoiceReportController extends Controller
{
    public function index()
    {
        $acct_invoice_item = AcctInvoiceItem::all();
        $core_client = CoreClient::all()->pluck('name','address');
        $core_product = CoreProduct::all();
        $acct_invoice = AcctInvoice::all()->pluck('invoice_id','invoice_status');
        $core_client_address = CoreClient::pluck('address','address');
        $paymentType=AppHelper::paymentType();
        return view('content.InvoiceReport.index', compact('acct_invoice','paymentType','acct_invoice_item','core_client','core_product','core_client_address'));
    }

    public function viewport(Request $request)
    {
        $sesi = array (
            "start_date"    => $request->start_date,
            // "client_id"    => $request->client_id,
            "end_date"    => $request->end_date,
            // "address"	    => $request->address,
            "view"		    => $request->view,
        );

        if($sesi['view'] == 'pdf'){
            $this->processPrinting($sesi);
        }else{
            $this->export($sesi);
        }
    }

    public function processPrinting($sesi){
        // $branch_id          = auth()->user()->branch_id;
        // $branch_status      = auth()->user()->branch_status;
        // $preferencecompany	= PreferenceCompany::select('company_name')->first();
        // $path               = public_path('storage/'.$preferencecompany);

        // if($branch_status == 1){
        //     if($sesi['branch_id'] == '' || $sesi['branch_id'] == 0){
        //         $branch_id = '';
        //     } else {
        //         $branch_id = $sesi['branch_id'];
        //     }           
        // }
        $invoice = AcctInvoice::with('product', 'client', 'items')
        // ->where('credits_approve_status', 1)
        // ->where('credits_account_status', 0)
        // ->where('credits_account_last_balance','>', 0)
        ->where('invoice_date','>=', Carbon::parse($sesi['start_date'])->format('Y-m-d'))
        ->where('invoice_date','<=', Carbon::parse($sesi['end_date'])->format('Y-m-d'))
        ->orderBy('invoice_no');
        // if(!empty($sesi['client_id'])){
        //     $creditacc = $creditacc->where('client_id', $sesi['client_id']);
        // }
        // if(!empty($branch_id)){
        //     $creditacc = $creditacc->where('branch_id', $branch_id);
        // }
        $invoice = $invoice->get();
        // dd($creditacc);
        $pdf = new TCPDF(['L', PDF_UNIT, 'A4', true, 'UTF-8', false]);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(6, 6, 6, 6);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('helvetica', 'B', 20);

        $pdf::AddPage('L');

        $pdf::SetFont('helvetica', '', 8);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $head = "<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
                        <tr>
                            <td><div style=\"text-align: center; font-size:14px\">DAFTAR PIUTANG</div></td>
                        </tr>
                        <tr>
				        <td><div style=\"text-align: center; font-size:10px\">Periode ".date('d-m-Y',strtotime($sesi['start_date']))." S.D. ".date('d-m-Y',strtotime($sesi['end_date']))."</div></td>
				        </tr>
                    </table>
        ";
        $pdf::writeHTML($head, true, false, false, false, '');
        $export = "
        <br><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\"><tr>
                <td width=\"3%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: left;font-size:9;\">No.</div></td>
                <td width=\"10%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">No. Invoice</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">Client</div></td>
                <td width=\"25%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">Alamat</div></td>
                <td width=\"17%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">Produk</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">Tipe</div></td>
                <td width=\"10%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:9;\">Status Pembayaran</div></td>
            </tr>";

        $no                 = 1;
        $totalplafon = 0;
        $totalangspokok = 0;
        $totalangsmargin = 0;
        $totaltotal = 0;
        $totalsisa = 0;
        //$pembayaran = ($invoice->invoice_status ? Apphelper::paymentType($invoice->payment_type) : AppHelper::paymentStatus($invoice->invoice_status));
        foreach ($invoice as $key => $val) {
                $export .= "
                <tr>
                <td width=\"3%\"><div style=\"text-align: center;\">".$no."</div></td>
                <td width=\"10%\"><div style=\"text-align: center;\">".$val['invoice_no']."</div></td>
                <td width=\"15%\"><div style=\"text-align: left;\">".$val->client->name."</div></td> 
                <td width=\"25%\"><div style=\"text-align: left;\">".$val->client->address."</div></td>
                <td width=\"17%\"><div style=\"text-align: left;\">".$val->product->name."</div></td>
                <td width=\"15%\"><div style=\"text-align: center;\">"."Divisi Software"."</div></td>
                <td width=\"10%\"><div style=\"text-align: center;\">". "Belum Dibayar" ."</div></td>
                </tr>";
                
                // $totalplafon += $val['credits_account_amount'];
                // $totalangspokok += $val['credits_account_principal_amount'];
                // $totalangsmargin += $val['credits_account_interest_amount'];
                // $totalsisa += $val['credits_account_last_balance'];
                // $totaltotal	+= $val['credits_account_payment_amount'];
				$no++;
		}
        $export .="<tr>
                        <td colspan =\"4\"><div style=\"font-size:9;text-align:left;font-style:italic\">Printed : ".date('d-m-Y H:i:s')."  ".Auth::user()->username."</div></td>
                    </tr></table>";
        //$pdf::Image( $path, 4, 4, 40, 20, 'PNG', '', 'LT', false, 300, 'L', false, false, 1, false, false, false);
        $pdf::writeHTML($export, true, false, false, false, '');

        $filename = 'DAFTAR PIUTANG- '.Carbon::now()->format('Y-m-d-Hisu').'.pdf';
        $pdf::Output($filename, 'I');
    }

    public function export($sesi){
        // $branch_id          = auth()->user()->branch_id;
        // $branch_status      = auth()->user()->branch_status;
        $preferencecompany	= PreferenceCompany::select('company_name')->first();
        $spreadsheet        = new Spreadsheet();

        // if($branch_status == 1){
        //     if($sesi['branch_id'] == '' || $sesi['branch_id'] == 0){
        //         $branch_id = '';
        //     } else {
        //         $branch_id = $sesi['branch_id'];
        //     }
        // }
        $invoice = AcctInvoice::with('product.type', 'client', 'items')
        
        // ->where('credits_approve_status', 1)
        // ->where('credits_account_status', 0)
        // ->where('credits_account_last_balance','>', 0)
        ->where('invoice_date','>=', Carbon::parse($sesi['start_date'])->format('Y-m-d'))
        ->where('invoice_date','<=', Carbon::parse($sesi['end_date'])->format('Y-m-d'))
        ->orderBy('invoice_no');
        // if(!empty($sesi['office_id'])){
        //     $invoice = $invoice->where('office_id', $sesi['office_id']);
        // }
        // if(!empty($branch_id)){
        //     $invoice = $invoice->where('branch_id', $branch_id);
        // }
        $invoice = $invoice->get();
        if(count($invoice)>=0){
            $spreadsheet->getProperties()->setCreator($preferencecompany['company_name'])
                                            ->setLastModifiedBy($preferencecompany['company_name'])
                                            ->setTitle("DAFTAR TAGIHAN ANGSURAN PINJAMAN")
                                            ->setSubject("")
                                            ->setDescription("DAFTAR TAGIHAN ANGSURAN PINJAMAN")
                                            ->setKeywords("DAFTAR, TAGIHAN, ANGSURAN, PINJAMAN")
                                            ->setCategory("DAFTAR TAGIHAN ANGSURAN PINJAMAN");

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $spreadsheet->getActiveSheet()->mergeCells("B1:L1");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->setCellValue('B1',"DAFTAR PIUTANG");

            $spreadsheet->getActiveSheet()->setCellValue('B3',"No");
            $spreadsheet->getActiveSheet()->setCellValue('C3',"No. Invoice");
            $spreadsheet->getActiveSheet()->setCellValue('D3',"Client");
            $spreadsheet->getActiveSheet()->setCellValue('E3',"Alamat");
            $spreadsheet->getActiveSheet()->setCellValue('F3',"Produk");
            $spreadsheet->getActiveSheet()->setCellValue('G3',"Tipe");
            $spreadsheet->getActiveSheet()->setCellValue('H3',"Status pembayaran");

            $no=0;
            $totalplafon = 0;
            $totalangspokok = 0;
            $totalangsmargin = 0;
            $totaltotal = 0;
            $j=4;
            foreach($invoice as $key=>$val){
                $no++;

                $spreadsheet->getActiveSheet()->getStyle('B'.$j.':H'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $spreadsheet->getActiveSheet()->setCellValue('B'.$j, $no);
                $spreadsheet->getActiveSheet()->setCellValueExplicit('C'.$j, $val['invoice_no'],'s');
                $spreadsheet->getActiveSheet()->setCellValue('D'.$j, $val->client->name);
                $spreadsheet->getActiveSheet()->setCellValue('E'.$j, $val->client->address);
                $spreadsheet->getActiveSheet()->setCellValue('F'.$j, $val->product->name);
                $spreadsheet->getActiveSheet()->setCellValue('G'.$j, $val->product->type->name);
                $spreadsheet->getActiveSheet()->setCellValue('H'.$j, "lunas");

                // $totalplafon += $val['credits_account_amount'];
                // $totalangspokok += $val['credits_account_principal_amount'];
                // $totalangsmargin += $val['credits_account_interest_amount'];
                // $totaltotal	+= $val['credits_account_payment_amount'];
                $j++;
            }

            $i = $j;

            $spreadsheet->getActiveSheet()->getStyle('B'.$i.':H'.$i)->getFill()->setFillType('solid')->getStartColor()->setRGB('FFFF00');
            // $spreadsheet->getActiveSheet()->getStyle('B'.$i.':H'.$i)->getBorders()->getAllBorders()->setBorderStyle('thin');
            // $spreadsheet->getActiveSheet()->mergeCells('B'.$i.':F'.$i);
            // $spreadsheet->getActiveSheet()->setCellValue('B'.$i, 'Total');

            // $spreadsheet->getActiveSheet()->setCellValue('G'.$i, number_format($totalplafon,2));
            // $spreadsheet->getActiveSheet()->setCellValue('H'.$i, number_format($totalangspokok,2));
            // $spreadsheet->getActiveSheet()->setCellValue('I'.$i, number_format($totalangsmargin,2));
            // $spreadsheet->getActiveSheet()->setCellValue('J'.$i, number_format($totaltotal,2));
            ob_clean();
            $filename='DAFTAR PIUTANG- '.Carbon::now()->format('Y-m-d-Hisu').'.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        }else{
            return redirect()->back()->with(['pesan' => 'Maaf data yang di eksport tidak ada !','alert' => 'warning']);
        }
    }   
}