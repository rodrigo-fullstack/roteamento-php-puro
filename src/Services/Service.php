<?php

namespace App\Services;

class Service{
    public function printData($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}