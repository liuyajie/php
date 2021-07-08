<?php

class CashContext
{
    private $cashObj = null;

    public function __construct($option = [])
    {
        switch ($option['type']) {
            case 1:
                $this->cashObj = new CashRebate($option['rebate']);
                break;
            case 2:
                $this->cashObj = new CashReturn();
                break;
            default:
                $this->cashObj = new CashNormal();
                break;
        }
    }

    public function getResult($money)
    {
        return $this->cashObj->acceptCash($money);
    }
}