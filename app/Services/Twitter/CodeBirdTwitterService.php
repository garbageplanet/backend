<?php

namespace App\Services\Twitter;

use Codebird\Codebird;
use App\Services\Twitter\Exceptions;

class CodeBirdTwitterService implements TwitterService
{
    protected $client;

    public function __construct( Codebird $client )
    {
      $this->client = $client;
    }

    public function sendTweet($data)
    {
        // $url = $data->url;

        dd($data);

        $params = [
            'status' => 'New garbage added to the map at {$url}',
            'lat'    => $data->lat,
            'long'   => $data->lng
        ];

        $tweet = $this->client->statuses_update($params);

        return $tweet
    }
}
