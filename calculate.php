<?php

$shop = new stdClass();

$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$MAX_FREQ=3;
$DATE_DIFF = 7;

if(isset($_POST['shopurl'])) {

    if(preg_match('/https:\/\/www\.e-food\.gr\/delivery\/.+/m',$_POST['shopurl'])){

        $shop->result=true;

        $storepage = get_data($_POST['shopurl']);


        $re = '/https:\/\/www\.e-food\.gr\/shop\/(\d+)\/logo/m';

        if (!preg_match_all($re, $storepage, $matches, PREG_SET_ORDER, 0)){
            goto abort;
        }

        $storeID = $matches[0][1];

        $shop->logo = $matches[0][0];

        $storeReviesRaw = file_get_contents("https://api.e-food.gr/api/v1/restaurants/". $storeID . "/ratings?limit=10000&comment_only=false");

        $storeReviesDecoded = (json_decode($storeReviesRaw));

        $shopInfo = json_decode(file_get_contents("https://api.e-food.gr/api/v1/restaurants/". $storeID));

        $stars = $shopInfo->data->information->average_rating;

        $stars = round($stars, 1);

        $shop_title = $shopInfo->data->information->title;

        $shop->stars=$stars;

        $shop->title=$shop_title;

        $comments=array();

        $starSum=0;

        foreach ($storeReviesDecoded->data as $jobj){
            $starSum += $jobj->overall_numeric;
            if($jobj->comment=="" && $jobj->overall_numeric==5) {
                array_push($comments,$jobj);
            }
        }

        $shop->sum=$starSum;

        $arrNames=array();
        $arrDates=array();

        foreach ($comments as $comment){
            if( isset($arrNames[$comment->first_name]) ) {
                $arrNames[$comment->first_name]++;
            }else{
                $arrNames[$comment->first_name] = 0;
            }
            $arrDates[$comment->first_name][]=$comment->created;
        }

        $punish_points = punish_points($arrNames, $arrDates);

        $score = round(($punish_points/sizeof($storeReviesDecoded->data))*100);

        $realStars = round(($starSum - $punish_points*5)/(sizeof($storeReviesDecoded->data)-$punish_points), 1);

        $shop->score = $score;

        $shop->realStars = $realStars;

        $shop->punishPoints = $punish_points;

        $shop->totalReviews = sizeof($storeReviesDecoded->data);
    }
    else {
        abort:
        $shop->result = false;
    }
}else{
    $shop->result = false;
}

$myJSON = json_encode($shop);
echo $myJSON;


function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function punish_points($arrNames, $arrDates){
    global $MAX_FREQ;
    global $DATE_DIFF;
    $punish_points=0;
    $last_date = NULL;
    foreach($arrNames as $name => $freq){
        if($freq > $MAX_FREQ){
            foreach ($arrDates[$name] as $date){
                if(!is_null($last_date)){
                    $datetime1 = new DateTime($last_date);
                    $datetime2 = new DateTime($date);
                    $interval = $datetime1->diff($datetime2);
                    if($interval->d < $DATE_DIFF){
                        $punish_points++;
                    }
                }
                $last_date = $date;
            }
        }
    }
    return $punish_points;
}