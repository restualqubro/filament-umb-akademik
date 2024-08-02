<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;

class Verified extends Controller
{
    
    public function cuti($id)
	{
		$data['title'] = "Data Pengajuan Cuti";
		$data['row'] = Surat::where('id', $id)->first();
		return view('validate/cuti', $data);
		
	}
}
