<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('ksort', [$this, 'sortByKey']),
            new TwigFilter('krsort', [$this, 'sortByKeyReverse']),
        ];
    }

    public function formatPrice($number, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price.' лв';

        return $price;
    }

    public function sortByKey($a)
    {
        if (isset($a->children)) {
            ksort($a->children);
        } else {
            krsort($a);
        }

        return $a;
    }

    public function sortByKeyReverse($a)
    {
        if (isset($a->children)) {
            krsort($a->children);
        } else {
            krsort($a);
        }

        return $a;
    }
}