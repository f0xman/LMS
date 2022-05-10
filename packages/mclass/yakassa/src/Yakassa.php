<?php

namespace Mclass\Yakassa;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

use App\Models\Error;

class Yakassa
{

    private const PAYMENT_URL = 'https://payment.yandex.net/api/v3/payments';
    private const RETURN_URL = 'https://mclass.pro/dashboard/success';
    private const API_URL = 'https://api.yookassa.ru/v3/payments';

    /** @var string */
    private $shopId;

    /** @var string */
    private $shopPassword;

    public function __construct(string $shopId, string $shopPassword)
    {
        $this->shopId = $shopId;
        $this->shopPassword = $shopPassword;
    }

    ////TODO Неплохо бы заебенить уведомление на почту
    private function processError(array $response, array $order)
    {
        
        $result = json_encode(array_merge($response, $order));

        $error_message = new Error([
            'type' => 'payment',
            'body' => $result
        ]);

        $error_message->save();

        return false;
    }


    public function getYakassaConfirmationUrl(array $order)
    {
        $response = Http::withBasicAuth($this->shopId, $this->shopPassword)
                                        ->withHeaders([
                                            'Idempotence-Key' => uniqid('', true),
                                            ])
                                        ->post(self::PAYMENT_URL, [
                                                        'amount' => array(
                                                            'value' => $order['amount'],
                                                            'currency' => 'RUB',
                                                        ),
                                                        'confirmation' => array(
                                                            'type' => 'redirect',
                                                            'return_url' => self::RETURN_URL.'/'.$order['order_id'],
                                                        ),
                                                        'metadata' => array(
                                                            'order_id' => $order['order_id'],
                                                            'seminar_id' => $order['seminar_id'],
                                                            'user_id' => $order['user_id']
                                                        ),
                                                        'capture' => true,
                                                        'description' => $order['description'],
                                            ]
                                        );

        $response = json_decode($response, true);

        return (isset($response['confirmation']['confirmation_url'])) ?
                $response :
                $this->processError($response, $order) ;
    }


    public function getYakassaStatus(string $yookassa_id)
    {
        $response = Http::withBasicAuth($this->shopId, $this->shopPassword)
                                        ->get(self::API_URL.'/'.$yookassa_id);

        $response = json_decode($response, true);

        return (isset($response['status'])) ? $response['status'] : false ;
    }

}
