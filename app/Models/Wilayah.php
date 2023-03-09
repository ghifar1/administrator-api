<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wilayah extends Model
{
    use HasFactory;

    public function getProvinsi(){
        $result = DB::table('provinsi')->select('*')->get();
        return $result;
    }

    public function getKotaKabupatenByProvinsi(int $parent){
        $result = DB::table('kota_kabupaten')->select('*')->where('parent', $parent)->get();
        return $result;
    }

    public function getKotaKabupaten(){
        $result = DB::table('kota_kabupaten')->select('*')->get();
        return $result;
    }

    public function GetKecamatanByKotaKabupaten(int $parent){
        $result = DB::table('kecamatan')->select('*')->where('parent', $parent)->get();
        return $result;
    }

    public function getKecamatan(){
        $result = DB::table('kecamatan')->select('*')->get();
        return $result;
    }

    public function GetKelurahanByKecamatan(int $parent){
        $result = DB::table('kelurahan')->select('*')->where('parent', $parent)->get();
        return $result;
    }

    public function getKelurahan(){
        $result = DB::table('kelurahan')->select('*')->get();
        return $result;
    }
}
