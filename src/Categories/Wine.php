<?php

namespace TPG\Pcflib\Categories;

class Wine extends Category
{
    public function region(string $region)
    {
        $this->attributes['Region'] = $region;
        return $this;
    }

    public function varietal(string $varietal)
    {
        $this->attributes['Varietal'] = $varietal;
        return $this;
    }

    public function volume(string $volume)
    {
        $this->attributes['Volume'] = $volume;
        return $this;
    }

    public function winery(string $winery)
    {
        $this->attributes['Winery'] = $winery;
        return $this;
    }
}