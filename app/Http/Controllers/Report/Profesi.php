<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Profesi extends Controller
{
    public static function generatePDF($id)
    {        
        // $image = QrCode::size(300)->generate('Embed this content into the QR Code');
        $data = [
            'title'     => 'PDF View',
            'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/profesi/'.$id)))

        ];
 
    	$pdf = PDF::loadview('filament.pages.report.profesi', $data);
    	return $pdf->stream();
        // return $image;

        // return view('filament.pages.report.cuti', $data);
    }
}
