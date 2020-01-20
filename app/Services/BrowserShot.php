<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot as Base;

class BrowserShot extends Base
{
    /**
     * @param float $scale
     *
     * @return BrowserShot
     */
    public function setScale(float $scale)
    {
        return $this->setOption('viewport.deviceScaleFactor', $scale);
    }
}
