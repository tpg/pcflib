<?php

namespace TPG\Pcflib\Categories;

class Videos extends Category
{
    const FORMAT_DVD = 'DVD';
    const FORMAT_LASER_DISC = 'Laser Disc';
    const FORMAT_VHS = 'VHS';
    const FORMAT_DOWNLOADABLE = 'Downloadable';
    const FORMAT_STREAMING = 'Streaming';

    protected $requiredAttributes = [
        'Format'
    ];

    public function format(string $format)
    {
        $this->attributes['Format'] = $format;
        return $this;
    }
}