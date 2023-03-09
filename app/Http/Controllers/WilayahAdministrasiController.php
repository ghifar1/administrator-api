<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use OpenApi\Annotations as OA;


/**
 * @OA\OpenApi(
 *  @OA\Info(
 *      title="Administration API",
 *      version="1.0.0",
 *      description="API documentation for Administration",
 *      @OA\Contact(
 *          email="contact@ghifar.dev"
 *      )
 *  ),
 *  @OA\Server(
 *      description="Returns App API",
 *      url="http://administration-api.org/api/"
 *  ),
 *  @OA\PathItem(
 *      path="/"
 *  )
 * )
 */

class WilayahAdministrasiController extends Controller
{
    private function wilayahModel(){
        return new Wilayah();
    }

    private function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    /**
     * @OA\Get (
     *     path="/wilayah/provinsi",
     *     tags={"Provinsi"},
     *     summary="Get Provinsi",
     *     operationId="getProvince",
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getProvinsi(){
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("provinsi");
        if(!$cache){
            $result = $this->wilayahModel()->getProvinsi();
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("provinsi", $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/provinsi/{parent_id}/kota_kabupaten",
     *     tags={"Kota/Kabupaten"},
     *     summary="Get Kota/Kabupaten from provinsi",
     *     operationId="getKotaKabupatenFromProvince",
     *     @OA\Parameter(
     *         name="parent_id",
     *         in="path",
     *         description="get this parent_id from id province",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKotaKabupatenFromProvinsi(int $parent) {
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kotakabupaten:".$parent);
        if(!$cache){
            $result = $this->wilayahModel()->getKotaKabupatenByProvinsi($parent);
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kotakabupaten:".$parent, $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/kota_kabupaten",
     *     tags={"Kota/Kabupaten"},
     *     summary="Get Kota/Kabupaten",
     *     operationId="getKotaKabupaten",
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKotaKabupaten(){
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kotakabupaten");
        if(!$cache){
            $result = $this->wilayahModel()->getKotaKabupaten();
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kotakabupaten", $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/kota_kabupaten/{parent_id}/kecamatan",
     *     tags={"Kecamatan"},
     *     summary="Get Kecamatan from kota/kabupaten",
     *     operationId="getKecamatanFromKotaKabupaten",
     *     @OA\Parameter(
     *         name="parent_id",
     *         in="path",
     *         description="get this parent_id from id kota_kabupaten",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKecamatanFromKotaKabupaten(int $parent) {
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kecamatan:".$parent);
        if(!$cache){
            $result = $this->wilayahModel()->GetKecamatanByKotaKabupaten($parent);
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kotakabupaten:".$parent, $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/kecamatan",
     *     tags={"Kecamatan"},
     *     summary="Get Kecamatan",
     *     operationId="getKecamatan",
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKecamatan(){
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kecamatan");
        if(!$cache){
            $result = $this->wilayahModel()->getKecamatan();
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kecamatan", $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/kecamatan/{parent_id}/kelurahan",
     *     tags={"Kelurahan"},
     *     summary="Get kelurahan from kecamatan",
     *     operationId="getKelurahanFromKecamatan",
     *     @OA\Parameter(
     *         name="parent_id",
     *         in="path",
     *         description="get this parent_id from id kecamatan",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKelurahanFromKotaKabupaten(int $parent) {
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kelurahan:".$parent);
        if(!$cache){
            $result = $this->wilayahModel()->GetKelurahanByKecamatan($parent);
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kelurahan:".$parent, $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @OA\Get (
     *     path="/wilayah/kelurahan",
     *     tags={"Kelurahan"},
     *     summary="Get Kelurahan",
     *     operationId="getKelurahan",
     *     @OA\Response(response="default", description="successful operation")
     * )
     */
    public function getKelurahan(){
        $startTime = microtime(true);
        $redis = Redis::connection();
        $cache = $redis->get("kelurahan");
        if(!$cache){
            $result = $this->wilayahModel()->getKelurahan();
            if(count($result) == 0){
                return response()->json([
                    'error' => 'data not found'
                ], 404);
            }
            $redis->set("kelurahan", $result, "EX", 500);
            $endTime = microtime(true);
            Logs::saveLog($this->getIp(), $endTime-$startTime, 'db');
            return response()->json($result);
        }
        $endTime = microtime(true);
        Logs::saveLog($this->getIp(), $endTime-$startTime, 'cache');
        return response($cache, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);
    }
}
