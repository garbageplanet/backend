<?php

namespace App;

use Log;
use Curl\Curl;
use Illuminate\Database\Eloquent\Model;

class Glome extends Model
{
    private static function getGlomeUrl($entry, $glomeid = null)
    {
        $type = 'post';
        $ret = env('GLOME_SERVER');

        switch($entry)
        {
            case 'accounts_create':
                $ret .= '/accounts/create';
                break;
            case 'accounts_show':
                $type = 'get';
                if ($glomeid) {
                    $ret .= '/accounts/' . $glomeid . '/show';
                }
                break;
            default:
                $ret = '';
        }

        if ($ret != '' and $type == 'get')
        {
            $ret .= '?apikey=' . env('GLOME_APIKEY') . '&apiuid=' . env('GLOME_APIUID');
        }

        Log::debug('Glome::getGlomeUrl: ' . $ret);
        return $ret;
    }

    public static function createGlomeAccount()
    {
        $data = [
            'apikey' => env('GLOME_APIKEY'),
            'apiuid' => env('GLOME_APIUID'),
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Glome::getGlomeUrl('accounts_create'));
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Timeout in seconds
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $resp = curl_exec($curl);
        curl_close($curl);

        Log::debug('Glome::createGlomeAccount: ' . $resp);

        return $resp;
    }

    public static function showGlomeAccount($glomeid = null)
    {
        if ($glomeid == '' or $glomeid == null) return null;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => Glome::getGlomeUrl('accounts_show', $glomeid)
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $outputArray = json_decode($resp, true);

        Log::debug('Glome::showGlomeAccount: ' . $resp);

        return $resp;
    }
}
