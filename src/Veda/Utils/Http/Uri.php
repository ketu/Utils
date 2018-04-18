<?php
/**
 * User: ketu.lai <ketu.lai@gmail.com>
 * Date: 2017/3/7
 * Time: 07:52
 */

namespace Veda\Utils\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    protected $scheme = '';
    protected $host = '';
    protected $port = '';
    protected $user = '';
    protected $password = '';
    protected $path = '';
    protected $query = '';
    protected $fragment = '';

    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function create()
    {
        return new static();
    }

    public static function fromString($url)
    {
        $parts = parse_url($url);

        $uri = new static();
        $uri->scheme = isset($parts['scheme']) ? $parts['scheme'] : '';
        $uri->host = isset($parts['host']) ? $parts['host'] : '';
        $uri->port = isset($parts['port']) ? $parts['port'] : '';
        $uri->user = isset($parts['user']) ? $parts['user'] : '';
        $uri->password = isset($parts['pass']) ? $parts['pass'] : '';
        $uri->path = isset($parts['path']) ? $parts['path'] : '';
        $uri->query = isset($parts['query']) ? $parts['query'] : null;
        $uri->fragment = isset($parts['fragment']) ? $parts['fragment'] : '';

        return $uri;
    }


    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }


    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }


    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }


    public function getPath(): string
    {
        return $this->path;
    }


    public function getQuery($asArray = false)
    {
        if ($asArray) {
            $queryParameters = [];
            foreach (explode('&', $this->query) as $query) {
                list($k, $v) = explode('=', $query);
                $queryParameters = array_merge($queryParameters, [$k => $v]);
            }
            return $queryParameters;
        }
        return $this->query;
    }


    public function getFragment(): string
    {
        return $this->fragment;
    }


    public function getUserInfo()
    {
        $userInfo = $this->user;
        if ($this->password) {
            $userInfo .= ':' . $this->password;
        }
        return $userInfo;
    }

    public function getAuthority()
    {
        $authority = $this->host;
        if ($this->getUserInfo()) {
            $authority = $this->getUserInfo() . '@' . $authority;
        }
        if ($this->port) {
            $authority .= ':' . $this->port;
        }
        return $authority;
    }

    public function withScheme($scheme)
    {
        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    public function withUserInfo($user, $password = null)
    {
        $uri = clone $this;
        $uri->user = $user;
        if (null !== $password) {
            $uri->password = $password;
        }
        return $uri;
    }

    public function withHost($host)
    {
        $uri = clone $this;
        $uri->host = $host;
        return $uri;
    }

    public function withPort($port)
    {
        $uri = clone $this;
        $uri->port = $port;
        return $uri;
    }

    public function withPath($path)
    {
        $uri = clone $this;
        $uri->path = $path;
        return $uri;
    }

    public function withQuery($query)
    {

        $uri = clone $this;
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        $uri->query = $query;

        return $uri;
    }

    public function withFragment($fragment)
    {
        $uri = clone $this;
        $uri->fragment = $fragment;
        return $uri;
    }

    public function __toString()
    {
        $url = '';
        if ($this->getScheme()) {
            $url .= $this->getScheme() . '://';
        }

        if ($this->getAuthority()) {
            $url .= $this->getAuthority();
        }

        $url = rtrim($url, '/') .'/'. ltrim($this->getPath(), '/');

        if ($this->getQuery()) {
            $url .= '?' . $this->getQuery();
        }

        if ($this->getFragment()) {
            $url .= '#' . $this->getFragment();
        }

        return $url;
    }

}
