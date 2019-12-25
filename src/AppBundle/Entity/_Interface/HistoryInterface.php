<?php


namespace AppBundle\Entity\_Interface;


use DateTime;

interface HistoryInterface
{
    public function getDateAdded(): DateTime;
    public function getProductsCount(): int;
    public function getActivityType(): string;
}