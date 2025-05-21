<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Envios;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorSVG;


class ticketController extends Controller
{
    public function generarTicket(Request $request)
    {
        // Generar el código de barras usando el tracking id del envío
        $barcodeGenerator = new BarcodeGeneratorSVG();
        $barcodeData = $barcodeGenerator->getBarcode($request->codigoproducto, $barcodeGenerator::TYPE_CODE_128);
        $barcodeUrl = 'data:image/svg+xml;base64,' . base64_encode($barcodeData);

        $data = [
            'ticket' => $request,
            'barcode' => $barcodeUrl
        ];

        $pdf = Pdf::loadView('ticket', $data)->setPaper([0, 0, 288, 432]); // 288x432 pts = 4"x6"

        return $pdf->stream('ticket.pdf');
    }

    public function generarSecondTicket(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'remitente' => 'required|max:255',
            'destinatario' => 'required|max:255',
            'consolidado' => 'required',
            'origen' => 'required',
            'destino' => 'required',
            'fecha' => 'required',
            'transporte' => 'required',
            'agencia' => 'required',
            'cantidad' => 'required|numeric',    
            'peso' => 'required|numeric',
        ]);

        if($validator->fails()){
            return null;
        }

        $barcodeGenerator = new BarcodeGeneratorSVG();
        $barcodeData = $barcodeGenerator->getBarcode($request->consolidado, $barcodeGenerator::TYPE_CODE_128);
        $barcodeUrl = 'data:image/svg+xml;base64,' . base64_encode($barcodeData);

        $data = [
            'ticket' => $request,
            'barcode' => $barcodeUrl,
        ];

        $pdf = Pdf::loadView('secondticket', $data)->setPaper([0, 0, 288, 432]); // 288x432 pts = 4"x6"

        return $pdf->stream('ticket.pdf');
    }
}
