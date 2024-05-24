<?php


namespace App\Traits\Helpers;

use Illuminate\Support\Facades\Hash;

trait IdTrait{

    public function hashID(int $length = 5){
        return bin2hex(random_bytes($length));
    }
}
