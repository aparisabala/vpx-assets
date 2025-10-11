<?php

namespace App\Traits\Apis\Sms;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Instasent\SMSCounter\SMSCounter;

trait ApiSms
{
    public function  singleSmsPrice($sms, $numbers, $rate)
    {
        $smsCounter = new SMSCounter();
        $c          = $smsCounter->count($sms);
        return $c->messages * $numbers * $rate;
    }

    public function AdminSms($sms, $numbers)
    {   
        $app_data = $this->AppData->getQuery(['type'=>'first','query'=>['where'=> [[['id','=',1]]]]]);
        if (!empty($app_data)) {
            Log::info("found app");
            $price = $this->singleSmsPrice($sms, count($numbers), $app_data->sms_rate);
            if ($price < $app_data->sms_balance) {
                Log::info("bal avialbe");
                $this->SendSms($numbers, $sms);
                $app_data->sms_balance = $app_data->sms_balance - $price;
                $app_data->save();
            }
        }
    }
    public function SendSms($numbers, $sms, $send_type="one")
    {
        //if (env('SERVER_MODE') == 'SERVER') {
            //$app_data = AppData::find(1);
            $api_key  = "ZXeYpRRtGC5PQrh5MVDI";
            $senderid = "8809617611096";
            if ($send_type == "one") {

                $url      = "https://bulksmsbd.net/api/smsapi";
                $number  = implode(",", $numbers);
                $message = $sms;
                $data    = [
                    "api_key"  => $api_key,
                    "senderid" => $senderid,
                    "number"   => $number,
                    "message"  => $message,
                ];

            } else {

                $url      = "https://bulksmsbd.net/api/smsapimany";
                $message = [];
                foreach ($numbers as $key => $value) {

                    $message[] = [
                        "to"      => $value,
                        "message" => $sms[$key]
                    ];
                }

                $message = json_encode($message);
                $data = [
                    "api_key"  => $api_key,
                    "senderid" => $senderid,
                    "messages"  => $message,
                ];

            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        //}
    }
}