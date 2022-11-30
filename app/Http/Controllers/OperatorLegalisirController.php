<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengajuan;

class OperatorLegalisirController extends Controller
{
      public function viewAdd()
    {
    	return view('pages.OperatorLegalisir.add');
    }

    public function viewDashboard()
    {

    	$get = Pengajuan::getDataForOperatorLegalisir();
		$data = [];
		for($i=0; $i<count($get); $i++){
			if(!($get[$i]['jenis_pengambilan'] == 'Diambil' && $get[$i]['status'] == 'Legalisir Selesai')){
				$data[] = $get[$i];
			}
		}

   		return view('pages.OperatorLegalisir.dashboard')
   			->with('data', $data);
    }

    public function viewLegalisir($id_pengajuan)
    {

        $data = Pengajuan::getDataLegalisir($id_pengajuan);
        if($data['status']=='Pengajuan Diverifikasi')
        {
            Pengajuan::updateData([
            'id_pengajuan' => $id_pengajuan,
            'status' => 'Legalisir Diproses'
            ]);
        }
    	
    	$data = Pengajuan::getDataLegalisir($id_pengajuan);
    	return view('pages.OperatorLegalisir.legalisir')
    		->with('data', $data);
    }

    public function konfirmasi($id_pengajuan)
    {
    	Pengajuan::updateData([
    		'id_pengajuan' => $id_pengajuan,
    		'status' => 'Legalisir Selesai',
    		'tgl_legalisir' => date('Y-m-d')
		]);


		return redirect('OperatorLegalisir/dashboard')
		->with('success','Konfirmasi berhasil');
    }

    public function kirimBerkas($id_pengajuan)
    {
    	Pengajuan::updateData([
    		'id_pengajuan' => $id_pengajuan,
    		'status' => 'Berkas Dikirim',
    		'tgl_pengiriman' => date('Y-m-d')
		]);

		return back()->with('success','Berkas dikirim');
    }
    public function historiLegalisir()
    {
    	
    	$data=Pengajuan::getHistoriLegalisir();

		return view('pages.OperatorLegalisir.historilegalisir')
		->with('data', $data);
    }
    public function SearchData($keyword){
    	$data=Pengajuan::searchDataHistoriLegalisir($keyword);

    	return view('pages.OperatorLegalisir.historilegalisir')
      	->with('keyword', $keyword)
    	->with('data',$data);
    }
}
