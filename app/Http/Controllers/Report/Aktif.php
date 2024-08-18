<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan\Cuti as LayananCuti;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Aktif extends Controller
{
    public static function generatePDF($id)
    {        
        // $image = QrCode::size(300)->generate('Embed this content into the QR Code');        
        $data = [
            'title'     => 'PDF View',
            'items'     => DB::table('surat')
                            ->leftJoin('surat_aktif', 'surat.id', '=', 'surat_aktif.surat_id')
                            ->leftJoin('users', 'surat.mahasiswa_id', '=', 'users.id')
                            ->where('surat.id', $id)        
                            ->selectRaw('surat_aktif.no_surat, users.firstname, users.lastname, 
                            users.username, (SELECT prodi.nama_prodi from prodi 
                            join mahasiswa ON prodi.id = mahasiswa.prodi_id) as prodi,
                            (SELECT users.username from surat_aktif 
                            join users ON surat_aktif.wrektor_id = users.id) as nik,
                            (SELECT CONCAT(users.firstname, " ", users.lastname) from surat_aktif 
                            join users ON surat_aktif.wrektor_id = users.id) as nama
                            ')
                            ->get(),
            'tanggal'   => Carbon::now()->isoFormat('D MMMM Y'),
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];
 
    	$pdf = PDF::loadview('filament.pages.report.aktif', $data);
    	return $pdf->stream();
        // return $image;

        // return view('filament.pages.report.cuti', $data);
    }
}
