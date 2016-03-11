<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

/**
 * WakaTime API Wrapper
 *
 * @author Mario Basic <mario.basic@outlook.com>
 * @author Mohammad Emran Hasan <phpfour@gmail.com>
 *
 * @see https://github.com/mabasic/wakatime-php-api
 */
class WakaTime
{
    /** @var Client */
    protected $guzzle;

    /** @var  */
    protected $apiKey;

    /** @var string */
    protected $apiUri = 'https://wakatime.com/api/v1';

    public function __construct(Client $guzzle, $api_key)
    {
        $this->guzzle = $guzzle;
        $this->apiKey = $api_key;
    }

    protected function getHeaders()
    {
        return [
            'verify'  => 'ca-bundle.crt',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->apiKey),
            ],
        ];
    }

    /**
     * Makes a request to the URL and returns a response.
     *
     * @param  [type] $url
     * @return [type]
     */
    protected function makeRequest($resource)
    {
        $url = "{$this->apiUri}/{$resource}";

        $response = $this->guzzle->get($url, $this->getHeaders())->getBody();

        return json_decode($response, true);
    }

    /**
     * See: https://wakatime.com/developers#users for details.
     *
     * @return mixed
     */
    public function users($user)
    {
        return $this->makeRequest("users/{$user}");
    }

    /**
     * See: https://wakatime.com/developers#users for details.
     *
     * @return mixed
     */
    public function currentUser()
    {
        return $this->makeRequest("users/current");
    }

    /**
     * See: https://wakatime.com/developers#summaries for details.
     *
     * @param $startDate
     * @param $endDate
     * @param null $project
     * @param null|string $branches
     * @return mixed
     */
    public function summaries($startDate, $endDate, $project = null, $branches = null)
    {
        if ($project !== null) {
            $project = "&project={$project}";
        }
        if ($branches !== null) {
            $branches = "&branches={$branches}";
        }

        return $this->makeRequest("users/current/summaries?start={$startDate}&end={$endDate}" . $project . $branches);
    }

    /**
     * See: https://wakatime.com/developers#stats for details.
     *
     * @param $range
     * @param null $project
     * @return mixed
     */
    public function stats($range, $project = null)
    {
        if ($project !== null) {
            $project = "?project={$project}";
        }

        return $this->makeRequest("users/current/stats/{$range}" . $project);
    }

    /**
     * See https://wakatime.com/developers#heartbeats for details.
     *
     * @param  [type] $date
     * @param  string $show
     * @return [type]
     */
    public function heartbeats($date, $show = null)
    {
        if ($show !== null) {
            $show = "?show={$show}";
        }

        return $this->makeRequest("users/current/heartbeats?date={$date}" . $show);
    }

    /**
     * See https://wakatime.com/developers#durations for details.
     *
     * @param $date
     * @param null $project
     * @param null|string $branches
     * @return mixed
     */
    public function durations($date, $project = null, $branches = null)
    {
        if ($project !== null) {
            $project = "&project={$project}";
        }
        if ($branches !== null) {
            $branches = "&branches={$branches}";
        }

        return $this->makeRequest("users/current/durations?date={$date}" . $project . $branches);
    }
}