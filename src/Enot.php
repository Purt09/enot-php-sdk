<?php

namespace Purt09\Enot;

use DomainException;

class Enot
{
    const LINK = "https://enot.io/pay?";
    const LINK_REQUEST = "https://enot.io/request/payment-methods?";
    const LINK_INFO = "https://enot.io/request/payment-info?";

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

    public function request()
    {
        $params = [
            'merchant_id' => $this->id,
            'secret_key' => $this->secret_key,
        ];
        return file_get_contents(self::LINK_REQUEST . http_build_query($params));
    }

    public function paymentInfo($api_key, $email, $id)
    {
        $params = [
            'api_key' => $api_key,
            'email' => $email,
            'id' => $id,
            'shop_id' => $this->id
        ];
        return file_get_contents(self::LINK_INFO . http_build_query($params));
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