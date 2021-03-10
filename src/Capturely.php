<?php

namespace Capturely;

use Capturely\Responses\ApiErrorException;
use Capturely\Structures\Pdf;
use Capturely\Structures\Screenshot;
use Closure;
use Illuminate\Support\Traits\ReflectsClosures;
use RuntimeException;

class Capturely
{
    use ReflectsClosures;

    protected ?string $html = null;

    protected ?string $url = null;

    protected ?array $pdf = [];

    protected ?array $screenshot = [];

    protected array $viewport = [];

    protected array $cookies = [];

    protected ?string $userAgent = null;

    protected array $authentication = [];

    protected array $extraHttpHeaders = [];

    protected array $waitUntil = [];

    protected ?string $emulateMediaType = null;

    protected bool $stream = false;

    protected ?string $s3_bucket = null;

    protected ?string $s3_path = null;

    protected ?string $s3_filename = null;

    public static function html(string $html)
    {
        return (new static())->setHtml($html);
    }

    public static function url(string $url)
    {
        return (new static())->setUrl($url);
    }

    public function setHtml(string $html) : Capturely
    {
        $this->html = $html;

        return $this;
    }

    public function setUrl(string $url) : Capturely
    {
        $this->url = $url;
        $this->html = null;

        return $this;
    }

    public function pdf(?Closure $pdf = null) : Capturely
    {
        $this->screenshot = [];

        $this->pdf = $pdf ? $this->resolveClosure($pdf) : (new Pdf())->toArray();

        return $this;
    }

    public function screenshot(?Closure $screenshot = null) : Capturely
    {
        $this->pdf = [];

        $this->screenshot = $screenshot ? $this->resolveClosure($screenshot) : (new Screenshot())->toArray();

        return $this;
    }

    public function viewport(Closure $viewport) : Capturely
    {
        $this->viewport = $this->resolveClosure($viewport);

        return $this;
    }

    public function withCookies(Closure $cookies) : Capturely
    {
        $this->cookies = $this->resolveClosure($cookies);

        return $this;
    }

    public function userAgent(string $userAgent) : Capturely
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function authentication(string $username, string $password) : Capturely
    {
        $this->authentication = ['credentials' => compact('username', 'password')];

        return $this;
    }

    public function extraHttpHeaders(array $headers) : Capturely
    {
        $this->extraHttpHeaders = $headers;

        return $this;
    }

    public function waitUntil(Closure $waitUntil) : Capturely
    {
        $this->waitUntil = current($this->resolveClosure($waitUntil));

        return $this;
    }

    public function emulatePrint() : Capturely
    {
        $this->emulateMediaType('print');

        return $this;
    }

    public function emulateScreen() : Capturely
    {
        $this->emulateMediaType('screen');

        return $this;
    }

    protected function emulateMediaType(string $type) : Capturely
    {
        $this->emulateMediaType = in_array($type, ['screen', 'print']) ? strtolower($type) : null;

        return $this;
    }

    public function toCloud()
    {
        $this->toS3(config('filesystems.cloud'));

        return $this;
    }

    public function toS3($bucket, $path = null, $filename = null)
    {
        $this->s3_bucket = $bucket;
        $this->s3_path = $path;
        $this->s3_filename = $filename;

        return $this;
    }

    public function serializePayload()
    {
        $payload = [
            'options' => [
                'pdf' => $this->pdf,
                'screenshot' => $this->screenshot,
            ],
            'from' => [
                'url' => $this->url,
                'html' => $this->html,
            ],
            'cookies' => $this->cookies,
            'viewport' => $this->viewport,
            'authentication' => $this->authentication,
            'extraHttpHeaders' => $this->extraHttpHeaders,
            'waitUntil' => $this->waitUntil,
            'userAgent' => $this->userAgent,
            'emulateMediaType' => $this->emulateMediaType,
            'stream' => $this->stream,
        ];

        return $this->array_filter_recursive($payload);
    }

    /**
     * @return Responses\ConversionResponse|\Symfony\Component\HttpFoundation\StreamedResponse|null
     * @throws ApiErrorException
     */
    public function stream()
    {
        $this->stream = true;

        return $this->capture();
    }

    /**
     * @return Responses\ConversionResponse|\Symfony\Component\HttpFoundation\StreamedResponse|null
     * @throws ApiErrorException
     */
    public function capture()
    {
        $api = new Api($this->serializePayload());

        return $api->post();
    }

    protected function resolveClosure(Closure $closure)
    {
        $class = $this->firstClosureParameterType($closure);

        $structure = $closure(new $class());

        if (is_null($structure)) {
            throw new RuntimeException('You must return your PDF/Screenshot call');
        }

        return $structure->toArray();
    }

    protected function array_filter_recursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->array_filter_recursive($value);
            }
        }

        return array_filter($input);
    }
}
