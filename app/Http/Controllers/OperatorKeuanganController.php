<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengajuan;

class OperatorKeuanganController extends Controller
{
    public function viewAdd()
    {
    	return view('pages.AdminKeuangan.add');
    }
    public function viewAdd2()
    {
    	return view('pages.AdminKeuangan.add2');
    }

    public function viewDashboard()
   	{
   		$data = Pengajuan::getDataForOperatorKeuangan();
      for($i=0; $i<count($data); $i++){
            $data[$i]['tgl_pengajuan'] = date('d-m-Y', strtotime($data[$i]['tgl_pengajuan']));
        }
   		return view('pages.OperatorKeuangan.dashboard')
   			->with('data', $data);
   	}

    public function index()
    {   
        $nim  = Auth::user()->nim;
        $data = Pengajuan::getDataMahasiswa($nim);
        for($i=0; $i<count($data); $i++){
            $data[$i]['tgl_pengajuan'] = date('d-m-Y', strtotime($data[$i]['tgl_pengajuan']));
        }
        return view('home')->with('data',$data);
    }

   	public function viewHistoriPengajuan()
   	{

   		$data = Pengajuan::getHistoriPengajuan();
   		return view('pages.OperatorKeuangan.historiPengajuan')
   			->with('data', $data);
   	}

   	public function verifikasi($id_pengajuan)
   	{
     		Pengajuan::updateData([
     			'id_pengajuan' => $id_pengajuan,
     			'status' => 'Pengajuan Diverifikasi'
  		]);

		  return redirect('OperatorKeuangan/dashboard');
   	}

    public function tolak(Request $req)
    {
      //dd($req);
        // Pengajuan::updateData([
        //   'id_pengajuan' => $id_pengajuan,
        //   'status' => 'Ditolak'


      Pengajuan::updateData([
        'id_pengajuan'=>$req->id_pengajuan,
        'status' => 'Ditolak',
        'alasan_tolak' =>$req->alasan,
        'komentar'=>$req->komentar
      ]);

      
      return redirect('OperatorKeuangan/dashboard');
    }

    public function viewdetailPembayaran($id_pengajuan){
      
      $data = Pengajuan::getDataLegalisir($id_pengajuan);
      
      return view('pages.OperatorKeuangan.detail')
      ->with('data',$data);

    }

    public function SearchData($keyword){
      $data=Pengajuan::searchDataHistoriPengajuanKeuangan($keyword);

      return view('pages.OperatorKeuangan.historiPengajuan')
      ->with('keyword', $keyword)
      ->with('data',$data);
    }
}
