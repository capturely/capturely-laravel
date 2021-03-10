<?php

namespace Capturely\Structures;

class Screenshot extends AbstractStructure
{
    public const JPEG = 'jpeg';

    public const PNG = 'png';

    public const BINARY = 'binary';

    public const BASE64 = 'base64';

    protected string $type = self::PNG;

    protected ?int $quality = null;

    protected bool $fullPage = false;

    protected ?array $clip;

    protected bool $omitBackground = false;

    protected string $encoding = self::BINARY;

    public function jpeg($quality = 90) : Screenshot
    {
        $this->type = self::JPEG;
        $this->quality = $quality;

        return $this;
    }

    public function png() : Screenshot
    {
        $this->type = self::PNG;

        $this->quality = null;

        return $this;
    }

    public function fullPage(bool $fullPage = true) : Screenshot
    {
        $this->clip = null;

        $this->fullPage = $fullPage;

        return $this;
    }

    public function clip(int $x, int $y, int $width, int $height) : Screenshot
    {
        $this->fullPage = false;

        $this->clip = compact('x', 'y', 'width', 'height');

        return $this;
    }

    public function omitBackground(bool $omitBackground = true) : Screenshot
    {
        $this->omitBackground = $omitBackground;

        return $this;
    }

    public function binary() : Screenshot
    {
        $this->encoding = self::BINARY;

        return $this;
    }

    public function base64() : Screenshot
    {
        $this->encoding = self::BASE64;

        return $this;
    }

}
