<?php

namespace TPG\Pcflib\Categories;

class Clothing extends Category
{
    protected $requiredAttributes = [
        'AgeGroup',
        'Colour',
        'Gender',
        'Size',
    ];

    public function ageGroup(string $ageGroup)
    {
        $this->attributes['AgeGroup'] = $ageGroup;
        return $this;
    }

    public function colour(string $colour)
    {
        $this->attributes['Colour'] = $colour;
        return $this;
    }

    public function gender(string $gender)
    {
        $this->attributes['Gender'] = $gender;
        return $this;
    }

    public function size(string $size)
    {
        $this->attributes['Size'] = $size;
        return $this;
    }

    public function material(string $material)
    {
        $this->attributes['Material'] = $material;
        return $this;
    }

    public function pattern(string $pattern)
    {
        $this->attributes['Pattern'] = $pattern;
        return $this;
    }

    public function sizeType(string $sizeType)
    {
        $this->attributes['SizeType'] = $sizeType;
        return $this;
    }

    public function style(string $style)
    {
        $this->attributes['style'] = $style;
        return $this;
    }
}