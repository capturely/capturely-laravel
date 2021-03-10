<?php

namespace Capturely\Responses;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;

class ConversionResponse implements Responsable, Jsonable
{
    public ?string $url;

    public ?int $size;

    public function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function toResponse($request)
    {
        return get_object_vars($this);
    }

    public function toJson($options = 0)
    {
        return json_encode(get_object_vars($this), JSON_THROW_ON_ERROR);
    }
}
