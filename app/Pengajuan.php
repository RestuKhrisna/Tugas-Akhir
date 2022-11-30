<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';
    public $timestamps = false;
    protected $keyType ='string';

    

    public static function getDataForOperatorKeuangan()
    {
    	return self::orderBy('status','desc')->get();
    }

    public static function getDataForOperatorLegalisir()
    {
    	return self::whereIn('status', ['Pengajuan Diverifikasi','Legalisir Diproses','Legalisir Selesai'])->orderBy('tgl_pengajuan','asc')->orderBy('id_pengajuan','asc')->get();
    }

    public static function searchDataHistoriLegalisir($keyword){
        $data = self::where('nim','like',"%$keyword%")
        ->orwhere('jenis_pengambilan','like',"%$keyword%")
        ->orwhere('alamat','like',"%$keyword%")
        ->orwhere('total_bayar','like',"%$keyword%")
        ->orwhere('tgl_pengajuan','like',"%$keyword%")
        ->orwhere('status','like',"%$keyword%")
        ->get();

        $return = [];
        for($i=0; $i<count($data); $i++){
            if($data[$i]['status'] == 'Berkas Dikirim' || $data[$i]['status'] == 'Legalisir Selesai'){
                $return[] = $data[$i];
            }
        }
        return $return;
    }

   
    public static function searchDataHistoriPengajuanKeuangan($keyword)
    {
        $data = self::where('nim','like',"%$keyword%")
        ->orwhere('id_pengajuan','like',"%$keyword%")
        ->orwhere('total_bayar','like',"%$keyword%")
        ->get();

        $return = [];
        for($i=0; $i<count($data); $i++){
            if($data[$i]['status'] != 'Pengajuan Diterima'){
                $return[] = $data[$i];
            }
        }
        return $return;
    }

    public static function getDataLegalisir($id_pengajuan)
    {
    	return self::where('id_pengajuan', $id_pengajuan)->first();
    }

    public static function getHistoriPengajuan()
    {
    	return self::where('status','!=', 'Pengajuan Diterima')->orderBy('id_pengajuan','dsc')->get();
    }

    public static function storeData($data)
    {
    	self::insert($data);
    }

    public static function updateData($data)
    {
    	self::where('id_pengajuan', $data['id_pengajuan'])
        ->update($data);
    }
     public static function getHistoriLegalisir()
    {
    	return self::whereIn('status', ['Legalisir Selesai','Berkas Dikirim'])->orderBy('id_pengajuan','dsc')->get();
    }
    public static function getDataMahasiswa($nim,$status='Semua status'){
        if($status=='Semua status'){
          return self::where('nim',$nim)->orderBy('tgl_pengajuan','dsc')->get();  
        }
        else{
            return self::where('nim',$nim)->where('status',$status)->orderBy('tgl_pengajuan','dsc')->get();
        }
    }
}
