<?php

namespace Purt09\Enot;

use DomainException;

class Enot
{
    const LINK = "https://enot.io/pay?";

    private $id;

    private $secret_key;

    private $secret_key_2;

    public function __construct(int $id, string $secret_key, string $secret_key_2)
    {
        $this->id = $id;
        $this->secret_key = $secret_key;
        $this->secret_key_2 = $secret_key_2;
    }

    public function generateSign(float $order_amount, string $payment_id): string
    {
        return md5($this->id.':'.$order_amount.':'.$this->secret_key.':'.$payment_id);
    }

    public function generateLink(float $sum, string $payment_id, $spec_value = "spec", string $currency = "RUB", string $comment = "comment")
    {
        $params = [
            'm' => $this->id,
            'oa' => $sum,
            'o' => $payment_id,
            's' => $this->generateSign($sum, $payment_id),
            'cf' => $spec_value,
            'cr' => $currency,
            'c' => $comment
        ];
        return self::LINK . http_build_query($params);
    }

    /**
     * @param array $params
     */
    public function checkSign(array $params)
    {
        $sign = md5($this->id.':'.$params['amount'].':'.$this->secret_key_2.':'.$params['merchant_id']);

        if ($sign != $params['sign_2']) {
            throw new DomainException('sign_2 not valid');
        }
    }
}