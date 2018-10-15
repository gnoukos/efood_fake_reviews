<?php

$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$shop = new stdClass();

$MAX_FREQ=3;
$DATE_DIFF = 7;

if(isset($_POST['shopurl'])) {

    if(preg_match('/https:\/\/www\.e-food\.gr\/delivery\/.+/m',$_POST['shopurl'])){

        $shop->result=true;

        $storeHtmlPage = get_data($_POST['shopurl']);
        
        $regEx = '/https:\/\/www\.e-food\.gr\/shop\/(\d+)\/logo/m';

        if (!preg_match_all($regEx, $storeHtmlPage, $matches, PREG_SET_ORDER, 0)){
            goto abort;
        }

        $shop->id = $matches[0][1];

        $shop->logo = $matches[0][0];

        $shopRatings = json_decode(file_get_contents("https://api.e-food.gr/api/v1/restaurants/". $shop->id . "/ratings?limit=10000&comment_only=false"));

        $shopInfo = json_decode(file_get_contents("https://api.e-food.gr/api/v1/restaurants/". $shop->id));

        $stars = $shopInfo->data->information->average_rating;

        $shop->stars = round($stars, 1);

        $shop->title = $shopInfo->data->information->title;
        
        
        
        $SuspiciousReviews=array();

        $starSum=0;

        foreach ($shopRatings->data as $shopRating){
            $starSum += $shopRating->overall_numeric;
            if($shopRating->comment=="" && $shopRating->overall_numeric==5) {
                array_push($SuspiciousReviews,$shopRating);
            }
        }


        $arrNames=array();
        $arrDates=array();

        foreach ($SuspiciousReviews as $comment){
            if( isset($arrNames[$comment->first_name]) ) {
                $arrNames[$comment->first_name]++;
            }else{
                $arrNames[$comment->first_name] = 0;
            }
            $arrDates[$comment->first_name][]=$comment->created;
        }

        $shop->punishPoints = punish_points($arrNames, $arrDates);

        $shop->score = round(($shop->punishPoints/sizeof($shopRatings->data))*100);

        $shop->realStars = round(($starSum - $shop->punishPoints*5)/(sizeof($shopRatings->data)-$shop->punishPoints), 1);

        $shop->totalReviews = sizeof($shopRatings->data);
    }
    else {
        abort:
        $shop->result = false;
    }
}else{
    $shop->result = false;
}

$shopData = json_encode($shop);
echo $shopData;


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