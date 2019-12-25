<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    const VAT = 20;

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('vat', [$this, 'getVat']),
            new TwigFilter('ksort', [$this, 'sortByKey']),
            new TwigFilter('krsort', [$this, 'sortByKeyReverse']),
            new TwigFilter('count', [$this, 'countCollection']),
        ];
    }

    public function formatPrice($number, $vat = true, $decimals = 2, $decPoint = '.', $thousandsSep = '')
    {

        if (!$vat) {
            $number = $number * (100 - self::VAT)/100;
        }

        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price.' лв';

        return $price;
    }

    public function getVat($number)
    {
        return ($number * self::VAT) / 100;
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

    public function countCollection($a)
    {
        if (isset($a->children)) {
            return count($a->children);
        } else {
            return count($a);
        }
    }
}