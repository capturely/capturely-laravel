<?php

namespace Capturely\Structures;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractStructure implements Arrayable
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}
