<?php

namespace Capturely\Structures;

class WaitUntil extends AbstractStructure
{
    protected array $waitUntil;

    public function load() : WaitUntil
    {
        $this->waitUntil[] = 'load';

        return $this;
    }

    public function domContentLoaded() : WaitUntil
    {
        $this->waitUntil[] = 'domcontentloaded';

        return $this;
    }

    public function networkIdle0() : WaitUntil
    {
        $this->waitUntil[] = 'networkidle0';

        return $this;
    }

    public function networkIdle2() : WaitUntil
    {
        $this->waitUntil[] = 'networkidle2';

        return $this;
    }
}
