<?php

class CashRebate implements CashSuper
{
    private $rebate = 1;

    public function __construct($rebate = 1)
    {
        $this->rebate = $rebate;
    }

    public function acceptCash($money)
    {
        return $money * $this->rebate;
    }
}