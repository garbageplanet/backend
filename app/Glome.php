<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glome extends Model
{
    
    public static function createGlomeAccount()
    {
        $data = [
            'apikey' => env('GLOME_APIKEY', '09a99d2393d103bb5409e5d72817184f'),
            'apiuid' => env('GLOME_APIUID', 'GarbagePlanet.20151116150220'),
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.glome.me/accounts/create');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     
        // Timeout in seconds
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($curl);
        curl_close($curl);

        $outputArray = json_decode($output);
        return $outputArray['glomeid'];
    }

    public static function showGlomeAccount($glomeid)
    {
        $data = [
            'apikey' => env('GLOME_APIKEY', '09a99d2393d103bb5409e5d72817184f'),
            'apiuid' => env('GLOME_APIUID', 'GarbagePlanet.20151116150220'),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.glome.me/accounts/'.$glomeid.'/show?apikey=09a99d2393d103bb5409e5d72817184f&apiuid=GarbagePlanet.20151116150220',
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $outputArray = json_decode($resp, true);
        return $outputArray['glomeid'];
    }
}
