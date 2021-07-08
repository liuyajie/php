<?php

class CashNormal implements CashSuper
{

    public function acceptCash($money)
    {
        return $money;
    }
}