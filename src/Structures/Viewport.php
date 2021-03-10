<?php

namespace Capturely\Structures;

class Viewport extends AbstractStructure
{
    protected int $width = 1920;

    protected int $height = 1080;

    protected int $deviceScaleFactor = 1;

    protected bool $isMobile = false;

    protected bool $hasTouch = false;

    protected bool $isLandscape = false;

    public function windowSize(int $width, int $height) : Viewport
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function deviceScaleFactor(int $deviceScaleFactor) : Viewport
    {
        $this->deviceScaleFactor = $deviceScaleFactor;

        return $this;
    }

    public function isMobile(bool $isMobile = true) : Viewport
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    public function hasTouch(bool $hasTouch = true) : Viewport
    {
        $this->hasTouch = $hasTouch;

        return $this;
    }

    public function isLandscape(bool $isLandscape = true) : Viewport
    {
        $this->isLandscape = $isLandscape;

        return $this;
    }
}
