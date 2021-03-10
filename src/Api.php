<?php

namespace Capturely;

use Capturely\Exceptions\CapturelyTokenNotSet;
use Capturely\Responses\ApiErrorException;
use Capturely\Responses\ConversionResponse;
use Capturely\Structures\Screenshot;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response as ResponseFacade;

class Api
{
    protected const API = 'https://api.capturely.app';

    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return ConversionResponse|\Symfony\Component\HttpFoundation\StreamedResponse|null
     * @throws ApiErrorException
     */
    public function post()
    {
        return $this->parseResponse($this->http()->post(static::API, $this->data));
    }

    /**
     * @return ConversionResponse|\Symfony\Component\HttpFoundation\StreamedResponse|null
     * @throws ApiErrorException
     */
    protected function parseResponse(Response $response)
    {
        if (!$response->successful()) {
            throw new ApiErrorException($response->body());
        }

        if ($this->isStream()) {
            return ResponseFacade::stream(function() use ($response) {
                echo $response->body();
            }, 200, [
                'Content-Type' => $response->header('Content-Type'),
            ]);
        }

        return new ConversionResponse(json_decode($response->body(), true));
    }

    protected function http() : PendingRequest
    {
        return Http::withHeaders([
            'Accept' => $this->acceptHeader(),
            'Content-Type' => 'application/json',
            'Authorization' => $this->token(),
        ]);
    }

    protected function token()
    {
        $token = config('services.capturely.token');

        if (empty($token)) {
            throw new CapturelyTokenNotSet('capturely token not set');
        }

        return $token;
    }

    protected function isPdf() : bool
    {
        return Arr::has($this->data, 'options.pdf');
    }

    protected function isScreenshot() : bool
    {
        return Arr::has($this->data, 'options.screenshot');
    }

    protected function isScreenshotBinary() : bool
    {
        return $this->isScreenshot()
            && Arr::get($this->data, 'options.screenshot.encoding', Screenshot::BINARY) === Screenshot::BINARY;
    }

    protected function isStream() : bool
    {
        return Arr::get($this->data, 'stream', false);
    }

    protected function acceptHeader() : string
    {
        if ($this->isStream()) {

            if ($this->isPdf()) {
                return 'application/pdf';
            }

            if ($this->isScreenshotBinary()) {
                $type = Arr::get($this->data, 'screenshot.type', Screenshot::PNG);

                return "image/{$type}";
            }

        }

        return 'application/json';
    }
}
