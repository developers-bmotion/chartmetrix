<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class MetricsController extends Controller
{
    public function socialIndex(Request $request)
    {
        $filters = $request->validate([
            'sortBy' => 'nullable|in:spotify_popularity, spotify_followers, spotify_monthly_listeners, twitter_followers, instagram_followers, wiki_views, soundcloud_followers, youtube_channel_views',
            'offset' => 'numeric',
            'limit' => 'numeric',
            'code2' => 'alf',
            'tagIds' => 'numeric',
            'subTagIds' => 'numeric',
            'since' => 'nullable',
            'until' => 'nullable',
        ]);

        $params = $this->getParams($filters);

        //https://api.chartmetric.com/api/album/3533190/applemusic/charts?since=2020-07-01&until=2020-07-01



        $url = "https://api.chartmetric.com/api/album/3533190/applemusic/charts?".$params;

        $url = "https://api.chartmetric.com/api/artist/1731220/instagram-audience-stats";

        $url = "https://api.chartmetric.com/api/artist/1731220/where-people-listen";
        return response()->json(self::execute($url));
    }


    public function artistNeighboring(Request $request)
    {
        $filters = $request->validate([
            'metric' => 'nullable'
        ]);

        $params = $this->getParams($filters);

        $url = "https://api.chartmetric.com/api/artist/".$request->input('id')."/neighboring-artists?".$params;
        return response()->json(self::execute($url));
    }

    private function getParams($filters){
        $params = "";
        foreach ($filters as $key => $filter) {
            if ($params !== "") {
                $params .= "&";
            }
            $params .= "{$key}={$filter}";
        }
        return $params;
    }


    private static function execute($url, $method = "GET"){
        $token = self::getToken();

        if ($method !== "GET") {
            $data = exec("curl -H 'Authorization: Bearer ".$token."' ".$url);
        } else {
            $data = exec("curl -H 'Authorization: Bearer ".$token."' -X ".$method." ".$url);
        }

        return json_decode($data);
    }


    private static function getToken()
    {
        $token = Cache::store('file')->get('token');

        if (!$token || true) {
            $token = exec('curl -d \'{"refreshtoken":"' . env('API_METRICS') . '"}\' -H "Content-Type: application/json" -X POST https://api.chartmetric.com/api/token');
            $tokenObject = json_decode($token);
            $token = $tokenObject->token;
            Cache::store('file')->put('token', $token, 3560);
        }

        return $token;
    }
}
