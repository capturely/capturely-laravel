<?php

namespace Capturely\Structures;

class Pdf extends AbstractStructure
{
    protected float $scale = 1;

    protected bool $displayHeaderFooter = false;

    protected ?string $headerTemplate;

    protected ?string $footerTemplate;

    protected bool $printBackground = false;

    protected bool $landscape = false;

    protected string $pageRanges = '';

    protected ?string $format = null;

    protected ?string $width = null;

    protected ?string $height = null;

    protected ?array $margin = null;

    protected bool $preferCSSPageSize = false;

    public function scale(float $scale) : Pdf
    {
        $this->scale = $scale;

        return $this;
    }

    public function displayHeaderFooter(bool $displayHeaderFooter = true) : Pdf
    {
        $this->displayHeaderFooter = $displayHeaderFooter;

        return $this;
    }

    public function printBackground(bool $printBackground = true) : Pdf
    {
        $this->printBackground = $printBackground;

        return $this;
    }

    public function landscape(bool $landscape = true) : Pdf
    {
        $this->landscape = $landscape;

        return $this;
    }

    public function letter() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function legal() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function tabloid() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function ledger() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a0() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a1() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a2() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a3() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a4() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a5() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function a6() : Pdf
    {
        return $this->format(__FUNCTION__);
    }

    public function format(string $format) : Pdf
    {
        $this->format = ucfirst($format);

        return $this;
    }

    public function width(string $width, $unit = 'mm') : Pdf
    {
        $this->width = $width.$unit;

        return $this;
    }

    public function height(string $height, $unit = 'mm') : Pdf
    {
        $this->height = $height.$unit;

        return $this;
    }

    public function margin(float $top, float $right, float $bottom, float $left, string $unit = 'mm') : Pdf
    {
        $this->margin = [
            'top' => $top.$unit,
            'right' => $right.$unit,
            'bottom' => $bottom.$unit,
            'left' => $left.$unit,
        ];

        return $this;
    }

    public function pageRanges(string $pageRanges) : Pdf
    {
        $this->pageRanges = $pageRanges;

        return $this;
    }

    public function preferCSSPageSize(bool $preferCSSPageSize = true) : Pdf
    {
        $this->preferCSSPageSize = $preferCSSPageSize;

        return $this;
    }

    public function headerTemplate(string $headerTemplate) : Pdf
    {
        $this->displayHeaderFooter();

        $this->headerTemplate = $headerTemplate;

        return $this;
    }

    public function footerTemplate(string $footerTemplate) : Pdf
    {
        $this->displayHeaderFooter();

        $this->footerTemplate = $footerTemplate;

        return $this;
    }

}
