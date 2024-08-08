<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan\Cuti as LayananCuti;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Aktif extends Controller
{
    public static function generatePDF($id)
    {        
        // $image = QrCode::size(300)->generate('Embed this content into the QR Code');
        $data = [
            'title'     => 'PDF View',
            'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/pindah/'.$id)))

        ];
 
    	$pdf = PDF::loadview('filament.pages.report.aktif', $data);
    	return $pdf->stream();
        // return $image;

        // return view('filament.pages.report.cuti', $data);
    }
}
