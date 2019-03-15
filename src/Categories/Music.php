<?php

namespace TPG\Pcflib\Categories;

class Music extends Category
{
    const FORMAT_CD = 'CD';
    const FORMAT_CASSETTE = 'Cassette';
    const FORMAT_LP = 'LP';
    const FORMAT_DVD = 'DVD';
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

    public function artist(string $artist)
    {
        $this->attributes['Artist'] = $artist;
        return $this;
    }

    public function label(string $label)
    {
        $this->attributes['Label'] = $label;
        return $this;
    }

    public function releaseDate($year, int $month, int $day)
    {
        $date = $year;

        if (get_class($year) !== \DateTime::class && !is_subclass_of($year, \DateTime::class)) {

            $date = new \DateTime(implode('-', [$year, $month, $day]));
        }

        $this->attributes['ReleaseDate'] = $date->format('Y-m-d');
        return $this;
    }
}