<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $data=User::getData();
        return view('pages.Admin.admin')
        ->with('data',$data);
    }

    public function viewEdit($id){
        $data=User::getData($id);
        return view('pages.Admin.edit')
        ->with('data',$data);
    }
    public function updateData(Request $req){
        $data=[
            'id'=>$req->id,
            'name'=>$req->name,
            'email'=>$req->email,
        ];

        if($req->password!=null){
            $data['password']=bcrypt($req->password);
            $data['password_asli']=$req->password;
        }

        User::updateData($data);  
        return back()->with('success','Berhasil mengubah data');     

    }

    public function hapus($id){
        $data=User::hapusData($id);
        return back()->with('success','Berhasil menghapus data');
    }
}
