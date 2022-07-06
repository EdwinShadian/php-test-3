<?php

namespace App;

use App\RequestParameters;

class Stats
{
    private int $views = 0;
    private int $traffic = 0;
    private array $urls = [];
    private int $urlsCount = 0;
    private array $crawlers = [];
    private array $statusCodes = [];
    private array $requestsParams = [];

    private const DEFAULT_CRAWLERS = [
        'Googlebot',
        'Slurp',
        'Yahoo! Slurp',
        'MSNBot',
        'Teoma',
        'Scooter',
        'ia_archiver',
        'Lycos',
        'Yandex',
        'StackRambler',
        'Mail.Ru',
        'Aport',
        'WebAlta',
        'WebAlta Crawler/2.0'
    ];

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $log = $this->parseLogToStrings($filePath);
        $this->parseStringsToRequestParams($log);
        $this->setStats($log);
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode(
            [
                'views' => $this->views,
                'traffic' => $this->traffic,
                'urls' => $this->urlsCount,
                'crawlers' => $this->crawlers,
                'statusCodes' => $this->statusCodes,
            ]
        );
    }

    /**
     * @param string $filePath
     * @return false|string[]
     */
    private function parseLogToStrings(string $filePath)
    {
        $log = file_get_contents($filePath);
        if (empty($log)) {
            echo 'File Open Error!';
        }

        return explode(PHP_EOL, $log);
    }

    /**
     * @param array $log
     * @return void
     */
    private function parseStringsToRequestParams(array $log)
    {
        foreach ($log as $logString) {
            $logString = explode(' ', $logString);
            $this->requestsParams[] = $this->setRequestParameters($logString);
        }
    }

    /**
     * @param array $log
     * @return void
     */
    private function setStats(array $log)
    {
        foreach ($this->requestsParams as $requestParams) {
            $this->addUrl($requestParams->url());
            $this->countTraffic($requestParams->traffic());
            $this->addStatusCode($requestParams->statusCode());
            $this->findCrawler($requestParams->userAgent());
        }
        $this->views = count($log);
        $this->countUrls();
    }

    /**
     * @param array $logString
     * @return RequestParameters
     */
    private function setRequestParameters(array $logString)
    {
        return new RequestParameters($logString[9], $logString[6], $logString[16], $logString[8]);
    }

    /**
     * @param string $url
     * @return void
     */
    private function addUrl(string $url)
    {
        if (!in_array($url, $this->urls)) {
            $this->urls[] = $url;
        }
    }

    /**
     * @return void
     */
    private function countUrls()
    {
        $this->urlsCount = count($this->urls);
    }

    /**
     * @param int $traffic
     * @return void
     */
    private function countTraffic(int $traffic)
    {
        $this->traffic += $traffic;
    }

    /**
     * @param string $userAgent
     * @return void
     */
    private function findCrawler(string $userAgent)
    {
        if (in_array($userAgent, Stats::DEFAULT_CRAWLERS)) {
            $this->addCrawlerCount($userAgent);
        }
    }

    /**
     * @param string $crawler
     * @return void
     */
    private function addCrawlerCount(string $crawler)
    {
        if (!array_key_exists($crawler, $this->crawlers)) {
            $this->crawlers[$crawler] = 1;
        } else {
            $this->crawlers[$crawler] += 1;
        }
    }

    /**
     * @param string $statusCode
     * @return void
     */
    private function addStatusCode(string $statusCode)
    {
        if (!array_key_exists($statusCode, $this->statusCodes)) {
            $this->statusCodes[$statusCode] = 1;
        } else {
            $this->statusCodes[$statusCode] += 1;
        }
    }
}