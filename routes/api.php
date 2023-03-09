<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahAdministrasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('wilayah')->group(function (){
   Route::get('provinsi', [WilayahAdministrasiController::class, 'getProvinsi']);
   Route::get('provinsi/{parent}/kota_kabupaten',
       [WilayahAdministrasiController::class, 'getKotaKabupatenFromProvinsi']);
   Route::get('kota_kabupaten', [WilayahAdministrasiController::class, 'getKotaKabupaten']);
   Route::get('kota_kabupaten/{parent}/kecamatan',
       [WilayahAdministrasiController::class, 'getKecamatanFromKotaKabupaten']);
    Route::get('kecamatan', [WilayahAdministrasiController::class, 'getKecamatan']);
    Route::get('kecamatan/{parent}/kelurahan',
        [WilayahAdministrasiController::class, 'getKelurahanFromKotaKabupaten']);
    Route::get('kelurahan', [WilayahAdministrasiController::class, 'getKelurahan']);
});
