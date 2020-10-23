<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Master_Agama;
use App\Warga;
use App\master_pekerjaan;
use \stdClass;

class HomeController
{
    public function index()
    {
        $rt = Auth::user()->rt_id;
        $chartBackground = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'];
        $agamaArray = [];
        $pekerjaanArray = [];
        $agamas = Master_Agama::all();        
        $pekerjaans = master_pekerjaan::all();

        foreach($agamas as $index => $agama){
            $dataObj = new \stdClass();
            $dataObj->nama_agama = $agama->religion_name;
            if ($rt != null) {
                $dataObj->count = Warga::where('warga.warga_religion', $agama->id)
                ->where('warga.warga_rt', $rt)
                ->count();
            
            }else{               
                $dataObj->count = Warga::where('warga.warga_religion', $agama->id)
                ->count();
            }
           
            if(($dataObj->count != 0) && (count($agamaArray) <= 6)){    
            $dataObj->backgroundColor = $chartBackground[$index];            
            array_push($agamaArray,$dataObj);
            }
        }

        foreach($pekerjaans as $index => $pekerjaan){
            $dataObj = new \stdClass();
            $dataObj->nama_pekerjaan = $pekerjaan->job_name;
            if ($rt != null) {
                $dataObj->count = Warga::where('warga.warga_religion', $pekerjaan->id)
                ->where('warga.warga_rt', $rt)
                ->count();
            
            }else{
                $dataObj->count = Warga::where('warga.warga_religion', $pekerjaan->id)
                ->count();           
            }
            
            if(($dataObj->count != 0) && (count($pekerjaanArray) <= 6)){    
            $dataObj->backgroundColor = $chartBackground[$index]; 
            array_push($pekerjaanArray,$dataObj);
            }
        }

        if ($rt != null) {
            $lakiLaki = Warga::where('warga.warga_sex', '1')
            ->where('warga.warga_rt', $rt)
            ->count();
            $perempuan = Warga::where('warga.warga_sex', '2')
            ->where('warga.warga_rt', $rt)
            ->count();
            $wargaBerdomisili = Warga::where('warga.warga_is_ktp_sama_domisili', '1')
            ->where('warga.warga_rt', $rt)
            ->count();
            $wargaNonBerdomisili = Warga::where('warga.warga_is_ktp_sama_domisili', '2')
            ->where('warga.warga_rt', $rt)
            ->count();
        }else{
            $lakiLaki = Warga::where('warga.warga_sex', '1')->count();
            $perempuan = Warga::where('warga.warga_sex', '2')->count();
            $wargaBerdomisili = Warga::where('warga.warga_is_ktp_sama_domisili', '1')->count();
            $wargaNonBerdomisili = Warga::where('warga.warga_is_ktp_sama_domisili', '2')->count();            
        }

      
        $user = Auth::user()->user_fullname;
        return view('home',compact('user','lakiLaki',
        'perempuan',
        'wargaBerdomisili',
        'wargaNonBerdomisili','agamaArray','pekerjaanArray'));
    }
}
