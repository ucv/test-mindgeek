<?php
/**
 * Created by PhpStorm.
 * User: uvale
 * Date: 11-Sep-18
 * Time: 21:02
 */

namespace App\Services;


use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;

class MgMovies
{
    const URL = 'https://mgtechtest.blob.core.windows.net/files/showcase.json';
    const CACHE_LIFETIME = 18000; // the TTL in seconds 30 min

    private $client;
    private $cacheDir;


    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return Client
     */
    private function getClient(){
        if(!$this->client){
            $stack = HandlerStack::create();
            $stack->push(new CacheMiddleware(
                new GreedyCacheStrategy(
                    new DoctrineCacheStorage(
                        new FilesystemCache($this->cacheDir)
                    )
                    ,self::CACHE_LIFETIME)
            ), 'curl_cache');
            $this->client = new Client(['handler' => $stack]);
        }
        return $this->client;
    }

    public function isValidUrl($url){
        $res = $this->getClient()->request('GET', $url, [
            'http_errors' => false,
            'connect_timeout' => 1,
        ]);
        return $res->getStatusCode() <= 400;
    }

    /**
     * @param $url
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getResource($url){
        $res = $this->getClient()->request('GET', $url, [
            'http_errors' => false
        ]);
        return $res;
    }

    public function getData($url = self::URL){
        $res = $this->getClient()->request('GET', $url, [
            'http_errors' => false
        ]);

        if($res->getStatusCode() == 200){
            return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $res->getBody()), true);
        }else{
            return [];
        }
    }
}