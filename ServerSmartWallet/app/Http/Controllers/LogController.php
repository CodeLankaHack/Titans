<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Log as Log;

class LogController extends Controller {

    //store logs in db
    public function store($lat, $lan, $event, $purse_id) {
        $data = array('lat' => $lat, 'lan' => $lan, 'event' => $event, 'purse_id' => $purse_id);
        $rules = array(
            'lat' => 'required',
            'lan' => 'required',
            'event' => 'required',
            'purse_id' => 'required'
        );
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json('Failed');
        } else {
            \App\Models\Log::create(array(
                'purse_id' => $purse_id,
                'lat' => $lat,
                'lan' => $lan,
                'event' => $event,
                'time' => date('Y-m-d H:i:s')));


            //send notification
            if ($event == 2) {
                $content = array(
                    "en" => 'Your purse is opened at Latitude: ' . $lat . ' Longitude: ' . $lan . ' at ' . date('Y-m-d H:i:s')
                );

                $headings = array('en' => 'Warning');

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
                return response()->json($return);
            } else {
                return response()->json('Success');
            }
        }
    }

    //retrieve data between two days
    public function get($startdate, $enddate) {
        $result = array();

        $logs = \DB::table('logs')->where('time', '>=', $startdate)
                ->where('time', '<=', $enddate)
                ->get();

        return response()->json($logs);
    }

    //retrieve all logs
    public function getAll() {
        echo $_GET['callback'] . "(" . json_encode(Log::all()) . ")";
    }

    //retrieve lastLog
    public function getLast() {

        $lastLog = \Illuminate\Support\Facades\DB::table('logs')->orderBy('id', 'desc')->take(1)->get();
        echo $_GET['callback'] . "(" .json_encode($lastLog[0]). ")";
    }

}
