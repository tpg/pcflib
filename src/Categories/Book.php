<?php

namespace TPG\Pcflib\Categories;

class Book extends Category
{
    const FORMAT_HARDCOVER = 'Hardcover';
    const FORMAT_SOFTCOVER = 'Soft Cover';
    const FORMAT_AUDIO = 'Audio';
    const FORMAT_EBOOK = 'eBook';

    protected $requiredAttributes = [
        'Format',
        'ISBN'
    ];


    public function format(string $format)
    {
        $this->attributes['Format'] = $format;
        return $this;
    }

    public function isbn(string $isbn)
    {
        $this->attributes['ISBN'] = $isbn;
        return $this;
    }

    public function author(string ...$authors)
    {
        $this->attributes['Author'] = $authors;
        return $this;
    }

    public function edition(int $edition)
    {
        $this->attributes['Edition'] = $edition;
        return $this;
    }

    public function genre(string $genre)
    {
        $this->attributes['Genre'] = $genre;
        return $this;
    }

    public function pages(int $pages)
    {
        $this->attributes['Pages'] = $pages;
        return $this;
    }

    public function publicationDate($year, int $month = null, int $day = null)
    {
        $date = $year;

        if (!get_class($year) === \DateTime::class && !is_subclass_of($year, \DateTime::class)) {

            $date = new \DateTime(implode('-', [$year, $month, $day]));
        }

        $this->attributes['PublicationDate'] = $date->format('Y-m-d');
        return $this;
    }

    public function publisher(string $publisher)
    {
        $this->attributes['Publisher'] = $publisher;
        return $this;
    }

    protected function getAuthorString()
    {
        return implode(',', $this->attributes['Author']);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'Author' => $this->getAuthorString(),
        ]);
    }
}