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

    public function testCheckSign2()
    {
        $secret_word2 = 'enot_secret_word2'; // ???
        $enot = new Enot(150, 'asd', $secret_word2);

        $params = [
            'merchant' => 150,
            'amount' => 200.00,
            'sign_2' => "5a9446513885f52cd8f9ec3066d87992"
        ];
    }
}