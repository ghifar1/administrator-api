<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Logs extends Model
{
    use HasFactory;

    static public function saveLog(string $ip, float $duration, string $type){
        $milisecond = round($duration * 1000);
        DB::table('logs')->insert([
            ['ip' => $ip, 'date' => new \DateTime(),'duration' => "$milisecond ms", 'type' => $type]
        ]);
    }
}
