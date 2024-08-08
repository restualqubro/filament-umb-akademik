<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Undur extends Controller
{
    public static function generatePDF($id)
    {        
        // $image = QrCode::size(300)->generate('Embed this content into the QR Code');
        $data = [
            'title'     => 'PDF View',
            'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/undur/'.$id)))

        ];
 
    	$pdf = PDF::loadview('filament.pages.report.undur', $data);
    	return $pdf->stream();
        // return $image;

        // return view('filament.pages.report.cuti', $data);
    }
}
