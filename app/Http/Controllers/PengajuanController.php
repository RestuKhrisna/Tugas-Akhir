<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pengajuan;


class PengajuanController extends Controller
{

      //   private function generateId()
      // {
      //   $get = Pengajuan::orderBy('id','DESC')->first();
      //   if($get){
      //     $tahun= date('Ymd_His')
      //     $last_number = substr($get['id'], 2, 4);
      //     $next = (int)$last_number + 1;
      //     return "TS".sprintf("%04d", $next);
      //   }
      //   else{
      //     return 'TS0001';
      //   }
      // }
    public function index()
    {   
        $status=isset($_GET['status'])?$_GET['status']:'Semua status';
        $nim  = Auth::user()->nim;
        $data = Pengajuan::getDataMahasiswa($nim,$status);
        for($i=0; $i<count($data); $i++){
            $data[$i]['tgl_pengajuan'] = date('d-m-Y', strtotime($data[$i]['tgl_pengajuan']));
        }
        return view('home')->with('data',$data);
    }
    public function detail($id_pengajuan)
    {
        $data = Pengajuan::getDataLegalisir($id_pengajuan);

        return view('pages.pengajuan.detail')
            ->with('data', $data);
    }
    public function viewAdd($jenis = null)
    {
    	return view('pages.pengajuan.add')
            ->with('jenis', $jenis);
    }

    public function store(Request $req)
    { 
    	// dd($req);

    	$file_ijazah = $this->saveIjazah($req);
    	$file_transkrip = $this->saveTranskrip($req);
    	$file_bukti_pembayaran = $this->saveBuktiPembayaran($req);
    	$total_bayar = $this->hitungTotalBayar($req);
        $id_pengajuan=$this->generateId($req->jenis);
        // $id_pengajuan=date('Ymd_His');

    	Pengajuan::storeData([
            'id_pengajuan'=>$id_pengajuan,
    		'nim' => Auth::user()->nim,
    		'file_transkrip' => $file_transkrip,
    		'file_ijazah' => $file_ijazah,
    		'jumlah_ijazah' => $req->jumlah_lembar_ijazah ?: 0,
    		'jumlah_transkrip' => $req->jumlah_lembar_transkrip ?: 0,
    		'bukti_pembayaran' => $file_bukti_pembayaran,
    		'jenis_pengambilan' => $req->jenis_pengambilan,
            'no_telpon'=>$req->nomer_telpon,
    		'alamat' => $req->alamat,
    		'total_bayar' => $total_bayar,
    		'tgl_pengajuan' => date('Y-m-d'),
    		'status' => 'Pengajuan Baru',
            'jenis_pengajuan' => $req->jenis
		]);

		return back()->with('success','Berhasil mengajukan legalisir');
    }

    private function hitungTotalBayar($req)
    {
    	$ijazah = isset($req->jumlah_lembar_ijazah) ? $req->jumlah_lembar_ijazah : 0;
    	$transkrip = isset($req->jumlah_lembar_transkrip) ? $req->jumlah_lembar_transkrip : 0;
    	$jumlah_lembar = $ijazah + $transkrip;
    	$jenis_pengambilan = $req->jenis_pengambilan;
    	$biaya_antar = 30000;
    	$minimal_biaya = 10000;
    	$biaya_per_lembar = 2500;

    	if($jumlah_lembar <= 4){
    		if($jenis_pengambilan == 'Diantar'){
    			return $biaya_antar + $minimal_biaya;
    		}
    		else{
    			return $minimal_biaya;
    		}
    	}
    	else{
    		if($jenis_pengambilan == "Diantar"){
    			return $biaya_antar + ($biaya_per_lembar * $jumlah_lembar);
    		}
    		else{
    			return $biaya_per_lembar * $jumlah_lembar;
    		}
    	}
    }

    private function generateId($jenis_pengajuan)
      {
        if($jenis_pengajuan=='ijazah'){
            $get = Pengajuan::where('jenis_pengajuan','ijazah')
                ->orderBy('id_pengajuan','dsc')->first();
            if($get){
              $last_number = substr($get['id_pengajuan'], 2, 4);
              $next = (int)$last_number + 1;
              return "I-".sprintf("%04d", $next);
            }
            else{
              return 'I-0001';
            }
        }
        elseif($jenis_pengajuan=='transkrip'){
            $get = Pengajuan::where('jenis_pengajuan','transkrip')
                ->orderBy('id_pengajuan','dsc')->first();
            if($get){
              $last_number = substr($get['id_pengajuan'], 2, 4);
              $next = (int)$last_number + 1;
              return "T-".sprintf("%04d", $next);
            }
            else{
              return 'T-0001';
            }
        }
        
      }

    private function saveIjazah($req)
    {
    	if($req->file_ijazah){
    		$file = $req->file_ijazah;
    		$ext = $file->getClientOriginalExtension();
    		$original_name = $file->getClientOriginalName();
    		$nim = Auth::user()->nim;
    		$waktu = date("Ymd_His");
    		$name = $nim.'_'.$waktu;
    		$file->move(public_path().'\\file_ijazah\\', $name.'.'.$ext);

    		return $name.'.'.$ext;
    	}
    	else{
    		return null;
    	}
    }

    private function saveTranskrip($req)
    {
    	if($req->file_transkrip){
    		$file = $req->file_transkrip;
    		$ext = $file->getClientOriginalExtension();
    		$original_name = $file->getClientOriginalName();
    		$nim = Auth::user()->nim;
    		$waktu = date("Ymd_His");
    		$name = $nim.'_'.$waktu;
    		$file->move(public_path().'\\file_transkrip\\', $name.'.'.$ext);

    		return $name.'.'.$ext;
    	}
    	else{
    		return null;
    	}
    }

    private function saveBuktiPembayaran($req)
    {
        //dd($req->file_bukti_pembayaran);

    	if($req->file_bukti_pembayaran){
    		$file = $req->file_bukti_pembayaran;
    		$ext = $file->getClientOriginalExtension();
    		$original_name = $file->getClientOriginalName();
    		$nim = Auth::user()->nim;
    		$waktu = date("Ymd_His");
    		$name = $nim.'_'.$waktu;
    		$file->move(public_path().'\\file_bukti_pembayaran\\', $name.'.'.$ext);

    		return $name.'.'.$ext;
    	}
    	else{
    		return null;
    	}
    }
}
