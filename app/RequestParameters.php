<?php

namespace App;

class RequestParameters
{
    private int $traffic;
    private string $url;
    private string $userAgent;
    private string $statusCode;

    /**
     * @param int $traffic
     * @param string $url
     * @param string $userAgent
     * @param string $statusCode
     */
    public function __construct(int $traffic, string $url, string $userAgent, string $statusCode)
    {
        $userAgent = explode('/', $userAgent);

        $this->userAgent = $userAgent[0];
        $this->traffic = $traffic;
        $this->url = $url;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function traffic()
    {
        return $this->traffic;
    }

    /**
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function userAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function statusCode()
    {
        return $this->statusCode;
    }
}
