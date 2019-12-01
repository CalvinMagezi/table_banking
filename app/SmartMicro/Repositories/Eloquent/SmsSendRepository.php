<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 23/11/2019
 * Time: 09:56
 */

namespace App\SmartMicro\Repositories\Eloquent;

use App\SmartMicro\Repositories\Contracts\SmsSendInterface;
use AfricasTalking\SDK\AfricasTalking;


class SmsSendRepository extends BaseRepository implements SmsSendInterface
{
    protected $model;

    protected $username = 'sandbox'; // use 'sandbox' for development in the test environment
    protected $from = "myShortCode or mySenderId";
    protected $apiKey   = 'ca51eabc72a7df6f8bab5bb0babd08c72ef5a81e5192a054d4099ed8928f461f';

    /**
     * SmsSendRepository constructor.
     */
    function __construct(){}

    /**
     * @param $recipients
     * @param $message
     * @return mixed|void
     * @throws \Exception
     */
    public function send($recipients, $message) {
        $AT = new AfricasTalking($this->username, $this->apiKey);
        $sms = $AT->sms();

        try {
            $result = $sms->send([
                'to'      => $recipients,
                'message' => $message,
               // 'from'    => $this->from
            ]);
        } catch (\Exception $e) {
           // throw $e;
            //Ignoring errors with sms api (They shouldn't stop payment process)
            //consider logging these errors for future tech support
        }
    }
}