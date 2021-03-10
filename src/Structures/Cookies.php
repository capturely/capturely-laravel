<?php

namespace Capturely\Structures;

class Cookies extends AbstractStructure
{
    protected array $cookies;

    public function make(string $name, string $value, ?string $url = null, ?string $domain = null, ?string $path = null, ?int $expires = null, ?bool $httpOnly = null, ?bool $secure = null, string $sameSite = 'Lax') : Cookies
    {
        $this->cookies[] = compact('name', 'value', 'url', 'domain', 'path', 'expires', 'httpOnly', 'secure', 'sameSite');

        return $this;
    }
}
