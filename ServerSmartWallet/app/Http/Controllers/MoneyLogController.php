<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\MoneyLog as MoneyLog;

class MoneyLogController extends Controller {

//store moneylogs in db
    public function store($lan, $lat, $money, $purse_id) {

        $data = array('lat' => $lat, 'lan' => $lan, 'money' => $money, 'purse_id' => $purse_id);
        $rules = array(
            'lat' => 'required',
            'lan' => 'required',
            'money' => 'required',
            'purse_id' => 'required'
        );
        $validator = \Validator::make($data, $rules); //validation
        if ($validator->fails()) {
            return response()->json('Failed');
        } else {
            \App\Models\MoneyLog::create(array(
                'purse_id' => $purse_id,
                'lat' => $lat,
                'lan' => $lan,
                'money' => $money,
                'time' => date('Y-m-d H:i:s')));

            //send notification
            $content = array();
            $headings = array();

            if ($money == 1) {
                $content["en"] = 'Money is withdrawed at Latitude: ' . $lat . ' Longitude: ' . $lan . ' at ' . date('Y-m-d H:i:s');
                $headings["en"] = 'Money withdrawal';
            }else{
                $content["en"] = 'Money is added at Latitude: ' . $lat . ' Longitude: ' . $lan . ' at ' . date('Y-m-d H:i:s');
                $headings["en"] = 'Money added';
            }

            $fields = array(
                'app_id' => "933f87d2-b68c-499d-a865-7e3be7badd9f",
                'included_segments' => array('All'),
                'contents' => $content,
                'headings' => $headings
            );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                'Authorization: Basic N2E0ZGU3OTEtYjUxZC00MzYxLTlmZGEtYmYwM2Q1YWZkM2E2'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            $return["allresponses"] = $response;
            echo json_encode($return);
        }
    }
    //retrieve data between two days
    public function get($startdate, $enddate) {
        $result = array();

        $logs = \DB::table('money_logs')->where('time', '>=', $startdate)
                ->where('time', '<=', $enddate)
                ->get();

        echo $_GET['callback'] . "(" . json_encode($logs) . ")";
    }

    //retrieve data today
    public function getToday() {
        $result = array();

        $logs = \DB::table('money_logs')->where('time', '>=', date('Y-m-d') . ' ' . '00:00:00')
                ->where('time', '<=', date('Y-m-d') . ' ' . '23:59:59')
                ->get();

        echo $_GET['callback'] . "(" . json_encode($logs) . ")";
    }

    //retrieve data all
    public function getAll() {
        echo $_GET['callback'] . "(" . json_encode(MoneyLog::all()) . ")";
    }

}
