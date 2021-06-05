<?php


use PHPUnit\Framework\TestCase;
use Purt09\Enot\Enot;

class EnotTest extends TestCase
{
    public function testGenerateSign(): void
    {
        $merchant_id = 150;
        $secret_word = 'enot_secret_word';
        $order_amount = 200;
        $payment_id = 99;

        $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$payment_id);

        $enot = new Enot($merchant_id, $secret_word, "asd");

        $this->assertEquals($sign, $enot->generateSign($order_amount, $payment_id));
    }
}