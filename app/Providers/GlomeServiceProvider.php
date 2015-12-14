<?php

namespace App\Providers;

use Log;
use Curl\Curl;
use Illuminate\Support\ServiceProvider;

class GlomeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    protected function configPath()
    {
        return __DIR__ . '/../../config/glome.php';
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('glome.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'glome');
    }

    /**
     * Forms Glome API request URLs by adding depending on the
     * API entry point
     */
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

        Log::debug('GlomeServiceProvider::getGlomeUrl: ' . $ret);
        return $ret;
    }

    /**
     *  Implements
     *  https://api.glome.me/simpleapi/#api-__Soft_Account_API-PostAccountsCreate
     */
    public static function createGlomeAccount()
    {
        $data = [
            'apikey' => env('GLOME_APIKEY'),
            'apiuid' => env('GLOME_APIUID'),
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, GlomeServiceProvider::getGlomeUrl('accounts_create'));
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Timeout in seconds
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $resp = curl_exec($curl);
        curl_close($curl);

        Log::debug('GlomeServiceProvider::createGlomeAccount: ' . $resp);

        return $resp;
    }

    /**
     *  Implements
     *  https://api.glome.me/simpleapi/#api-__Soft_Account_API-GetAccountsAccountShow
     */
    public static function showGlomeAccount($glomeid = null)
    {
        if ($glomeid == '' or $glomeid == null) return null;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => GlomeServiceProvider::getGlomeUrl('accounts_show', $glomeid)
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $outputArray = json_decode($resp, true);

        Log::debug('GlomeServiceProvider::showGlomeAccount: ' . $resp);

        return $resp;
    }
}
