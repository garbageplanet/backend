<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Services\Twitter\TwitterService;

class Trash extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'marked_by',
        'latlng',
        'amount',
        'todo',
        'image_url',
        'sizes',
        'embed',
        'note',
        'confirms',
        'cleaned'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**********************
     * Relationships begins
     */

    public function types()
    {
        return $this->hasMany('App\TrashType', 'trash_id');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag', 'trash_id');
    }

    public function confirms()
    {
        return $this->hasMany('App\Confirm', 'trash_id');
    }

    public function cleans()
    {
        return $this->hasMany('App\Clean', 'trash_id');
    }

    // TODO creator() vs user() for ownership?, aren't they the same
    public function creator()
    {
        return $this->belongsTo('App\User', 'marked_by');
    }

    /********************
     * Relationships ends
     */

    /**
     * make point with lat and long values
     * @return Illuminate\Database\Eloquent\Model
     */
    public function makePoint()
    {
        $query = "UPDATE trashes SET geom = ST_SetSRID(ST_MakePoint($this->latlng), 4326) WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;
    }

    // confirm the presence of garbage at a marker
    public function confirm()
    {
        $query = "UPDATE ONLY trashes SET confirms = confirms + 1  WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;
    }

    public function clean()
    {
        // toggle the current value in the db
        $query = "UPDATE ONLY trashes SET cleaned = NOT cleaned WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;
    }

    public function addTypes($types)
    {
        $types = explode(",", $types);
        foreach ($types as $type) {
            $this->types()->create(['type' => $type]);
        }
        return true;
    }

    /*public function notifyHelsinkiAboutTheTrash()
    { author villeglad@github

        list($lat, $lng) = explode(",", $this->latlng)

        if ($this->todo  = 3) {

            $description = 'garbagapla.net-palvelusta lähetetty ilmoitus merkittävästä roskan määrästä'

        } else if ($this->todo = 5) {

            $description = 'garbagapla.net-palvelusta lähetetty ilmoitus noudettavia roskasakkejä'
        }

        $data = [
            'api_key' => 'f1301b1ded935eabc5faa6a2ce975f6',
            'description' => $description,
            'service_code' => '246',
            'lat' => $lat,
            'long' => $lng,
            'media' => $this->image_url
        ];
        $ch = curl_init();
        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, 'http://dev.hel.fi/open311-test/v1/requests.json');
        // Set a referer
        curl_setopt($ch, CURLOPT_REFERER, "https://garbagepla.net");
        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        $output = curl_exec($ch);
        $outputArray = json_decode($output);

        //set service_id to the trash
        $this->helsinki_service_request_id = $outputArray[0]->service_request_id;
        $this->save();
    }*/

}
