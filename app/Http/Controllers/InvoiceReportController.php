<?php
namespace App\Http\Controllers;
use DateTime;
use App\Helpers\AppHelper;
use App\Models\CoreBranch;
use App\Models\CoreClient;
use App\Models\CoreMember;
use App\Models\CoreOffice;
use App\Models\AcctInvoice;
use App\Models\CoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AcctInvoiceItem;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\PreferenceCompany;
use App\Models\AcctCreditsAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\AcctSavingsMemberDetail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InvoiceReportController extends Controller
{
    public function index()
    {
        $acct_invoice = AcctInvoice::all()->pluck('invoice_id');
        $acct_invoice_item = AcctInvoiceItem::all();
        $core_product = CoreProduct::all();
        $core_client = CoreClient::all()->pluck('name','client_id');
        $core_client_addresses = CoreClient::pluck('address', 'address');
        return view('content.InvoiceReport.index', compact('acct_invoice','acct_invoice_item','core_product','core_client','core_client_addresses'));
    }

    public function viewport(Request $request)
    {
        $sesi = array (
            "start_date"    => $request->start_date,
            "client_id"    => $request->client_id,
            "end_date"    => $request->end_date,
            "address"	    => $request->address,
            "view"		    => $request->view,
        );

        if($sesi['view'] == 'pdf'){
            $this->processPrinting($sesi);
        }else{
            $this->export($sesi);
        }
    }
    
    public function processPrinting($sesi){
        $branch_id          = auth()->user()->branch_id;
        $branch_status      = auth()->user()->branch_status;
        $preferencecompany	= PreferenceCompany::select('company_name')->first();
       
        $path               = public_path('storage/'.$preferencecompany);
        

        if($branch_status == 1){
            if($sesi['branch_id'] == '' || $sesi['branch_id'] == 0){
                $branch_id = '';
            } else {
                $branch_id = $sesi['branch_id'];
            }
        }
        $invoice = AcctInvoice::with('client','items','product.type')
        ->where('invoice_date','>=', Carbon::parse($sesi['start_date'])->format('Y-m-d'))
        ->where('invoice_date','<=', Carbon::parse($sesi['end_date'])->format('Y-m-d'))
        ->orderBy('invoice_no');
        // $t = AppHelper::paymentStatus($invoice->invoice_status);
        // @dd($t);
        // if(!empty($sesi['office_id'])){
        //     $invoice = $invoice->wherne('office_id', $sesi['office_id']);
        // }
        // if(!empty($branch_id)){
        //     $invoice = $invoice->where('branch_id', $branch_id);
        // }
        $invoice = $invoice->get();
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

        $pdf::SetFont('helvetica', '', 10);

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
				        <td><div style=\"text-align: center; font-size:12px\">Periode ".date('d-m-Y',strtotime($sesi['start_date']))." S.D. ".date('d-m-Y',strtotime($sesi['end_date']))."</div></td>
				        </tr>
                    </table>
        ";
        $pdf::writeHTML($head, true, false, false, false, '');
        $export = "
        <br><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\"><tr>
                <td width=\"3%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">No.</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">No. Invoice</div></td>
                <td width=\"10%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Tanggal</div></td>
                <td width=\"17%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Client</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Alamat</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Produk</div></td>
                <td width=\"15%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Tipe</div></td>
                <td width=\"10%\" style=\"border-bottom: 1px solid black;border-top: 1px solid black\"><div style=\"text-align: center;font-size:10;\">Status Pembayaran</div></td>
            </tr>";

        $no                 = 1;
        $totalplafon = 0;
        $totalangspokok = 0;
        $totalangsmargin = 0;
        $totaltotal = 0;
        $totalsisa = 0;
        foreach ($invoice as $key => $val) {
                $export .= "
                <tr>
                <td width=\"3%\"><div style=\"text-align: center;\">".$no."</div></td>
                <td width=\"15%\"><div style=\"text-align: center;\">".$val['invoice_no']."</div></td>
                <td width=\"10%\"><div style=\"text-align: center;\">".$val['invoice_date']."</div></td>
                <td width=\"17%\"><div style=\"text-left: center;\">".$val->client->name."</div></td>
                <td width=\"15%\"><div style=\"text-left: center;\">".$val->client->address."</div></td>
                <td width=\"15%\"><div style=\"text-align: center;\">".$val->product->name."</div></td>
                <td width=\"15%\"><div style=\"text-align: center;\">".$val->product->type->name."</div></td>
                <td width=\"10%\"><div style=\"text-align: center;\">".AppHelper::paymentStatus($val->invoice_status)."</div></td>
                </tr>";
				$no++;
		}
        $export .="<tr>
                        <td colspan =\"4\"><div style=\"font-size:9;text-align:left;font-style:italic\">Printed : ".date('d-m-Y H:i:s')."  ".Auth::user()->username."</div></td>
                    </tr></table>";
        $pdf::Image( $path, 4, 4, 40, 20, 'PNG', '', 'LT', false, 300, 'L', false, false, 1, false, false, false);
        $pdf::writeHTML($export, true, false, false, false, '');

        $filename = 'DAFTAR PIUTANG - '.Carbon::now()->format('Y-m-d-Hisu').'.pdf';
        $pdf::Output($filename, 'I');
    }

    public function export($sesi){
        $branch_id          = auth()->user()->branch_id;
        $branch_status      = auth()->user()->branch_status;
        $preferencecompany	= PreferenceCompany::select('company_name')->first();
        $spreadsheet        = new Spreadsheet();

        $invoice = AcctInvoice::with('product','client','items')
        ->where('invoice_date','>=', Carbon::parse($sesi['start_date'])->format('Y-m-d'))
        ->where('invoice_date','<=', Carbon::parse($sesi['end_date'])->format('Y-m-d'))
        ->orderBy('invoice_no');

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
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
         

            $spreadsheet->getActiveSheet()->mergeCells("B1:L1");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B3:L3')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->setCellValue('B1',"DAFTAR TAGIHAN ANGSURAN PINJAMAN");

            $spreadsheet->getActiveSheet()->setCellValue('B3',"No");
            $spreadsheet->getActiveSheet()->setCellValue('C3',"No. Invoice");
            $spreadsheet->getActiveSheet()->setCellValue('D3',"Tanggal");
            $spreadsheet->getActiveSheet()->setCellValue('E3',"Client");
            $spreadsheet->getActiveSheet()->setCellValue('F3',"Alamat");
            $spreadsheet->getActiveSheet()->setCellValue('G3',"Produk");
            $spreadsheet->getActiveSheet()->setCellValue('H3',"Tipe");
            $spreadsheet->getActiveSheet()->setCellValue('I3',"Status Pembayaran");

            $no=0;
            $totalplafon = 0;
            $totalangspokok = 0;
            $totalangsmargin = 0;
            $totaltotal = 0;
            $j=4;
            foreach($invoice as $key=>$val){
                $no++;

                $spreadsheet->getActiveSheet()->getStyle('B'.$j.':I'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $spreadsheet->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
              

                $spreadsheet->getActiveSheet()->setCellValue('B'.$j, $no);
                $spreadsheet->getActiveSheet()->setCellValueExplicit('C'.$j, $val['invoice_no'],'s');
                $spreadsheet->getActiveSheet()->setCellValueExplicit('D'.$j, $val['invoice_date'],'s');
                $spreadsheet->getActiveSheet()->setCellValue('E'.$j, $val->client->name);        
                $spreadsheet->getActiveSheet()->setCellValue('F'.$j, $val->client->address);        
                $spreadsheet->getActiveSheet()->setCellValue('G'.$j, $val->client->name);        
                $spreadsheet->getActiveSheet()->setCellValue('H'.$j, "hai");
                $spreadsheet->getActiveSheet()->setCellValue('I'.$j, "Lunas");
                $j++;
            }

            $i = $j;
            $filename='DAFTAR PIUTANG - '.Carbon::now()->format('Y-m-d-Hisu').'.xls';
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

