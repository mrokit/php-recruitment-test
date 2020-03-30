<?php

interface Old_Legacy_CacheWarmer_Resolver_Interface
{
    public function getIp($hostname);
}

class Old_Legacy_CacheWarmer_Resolver_Method implements Old_Legacy_CacheWarmer_Resolver_Interface
{
    public function getIp($hostname)
    {
        return gethostbyname($hostname);
    }
}

class Old_Legacy_CacheWarmer_Actor
{
    private $callable;

    public function setActor($callable)
    {
        $this->callable = $callable;
    }

    public function act($hostname, $ip, $url, $time)
    {
        call_user_func($this->callable, $hostname, $ip, $url, $time);
    }
}

class Old_Legacy_CacheWarmer_Warmer
{
    /** @var Old_Legacy_CacheWarmer_Actor */
    private $actor;
    /** @var Old_Legacy_CacheWarmer_Resolver_Interface */
    private $resolver;
    /** @var string */
    private $hostname;

    /**
     * @param Old_Legacy_CacheWarmer_Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @param Old_Legacy_CacheWarmer_Resolver_Interface $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    public function warm($url)
    {
        $ip = $this->resolver->getIp($this->hostname);
        $url = $this->hostname . '/' . $url;

        $time = $this->getPageTime($url);
        $this->actor->act($this->hostname, $ip, $url, $time);
    }

    private function getPageTime($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (curl_exec($ch)) {
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
        }

        curl_close($ch);

        return $time;
    }
}
