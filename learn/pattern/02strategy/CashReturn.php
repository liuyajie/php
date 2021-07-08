<?php

class CashReturn implements CashSuper
{
    public function acceptCash($money)
    {
        if ($money >= 300) {
            return $money - 80;
        }
        return $money;
    }
}