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

	public function aktif($id)
	{
		$data['title'] = "Data Pengajuan Aktif";
		$data['row'] = Surat::where('id', $id)->first();
		return view('validate/aktif', $data);
		
	}

	public function pindah($id)
	{
		$data['title'] = "Data Pengajuan Pindah";
		$data['row'] = Surat::where('id', $id)->first();
		return view('validate/pindah', $data);
		
	}

	public function undur($id)
	{
		$data['title'] = "Data Pengajuan Undur";
		$data['row'] = Surat::where('id', $id)->first();
		return view('validate/undur', $data);
		
	}

	public function profesi($id)
	{
		$data['title'] = "Data Pengajuan Profesi";
		$data['row'] = Surat::where('id', $id)->first();
		return view('validate/profesi', $data);
		
	}
}
