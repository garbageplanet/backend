<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class MapFeatureCreated extends Notification
{

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwitterChannel::class];
    }

    public function toTwitter($post)
    {

              list($lat, $lng) = explode(",", $post->latlng);

              $params = [
                  'status' => 'New garbage added to the map at https://garbagepla.net/' . $post->image_url,
                  'lat'    => $lat,
                  'lng'   => $lng,
              ];

              // dd($params);

        // return new TwitterStatusUpdate('New garbage added to the map', $post->latlng, $post->image_url);
        return new TwitterStatusUpdate('New garbage added to the map. #garbagerobot');
    }

}
