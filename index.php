<?php




// Start by getting the ticker symbol and assigning it to variable $ticker
extract($_REQUEST);

if(isset($ticker)){
//check if $ticker is set
$ticker = strtoupper($ticker);

//INSERT THE WANTED URLS TO BE FETCHED
//These need to be changed as the formats of the pages change over time
$reuters= "http://www.example.com". $ticker;
$nasdaq= "http://". $ticker;
$yahoo= "http://". $ticker;


//Try to retrieve 2nd urls price first (most reliable of 3 possible sources)
$reutResult = file_get_contents($reuters);
$nyArr1 = explode( 'font-size: 23px;">', $reutResult);
if($nyArr1[1]){
$nyArr2 = explode( "</span>", $nyArr1[1]);
if($nyArr2[1]){
$nyPrice = $nyArr2[0];
}
}


if($nyPrice){
    // We have 2nd price data for this stock
     $jsonResponse = '{"price": "'.floatval($nyPrice).'", "source": "2ndurls"}';
     echo json_encode($jsonResponse);
    return;

}



else{

//could not get 2ndurls, so trying 1st
 $nasResult = file_get_contents($nasdaq);   
 //Try to retrieve 1st price:
$nasArr1 = explode( "_LastSale1'>", $nasResult);
if($nasArr1[1]){
$nasArr2 = explode( "</label>", $nasArr1[1]);
if($nasArr2[1]){
$nasPrice = $nasArr2[0];
}
}


if($nasPrice){
    //we have 1sturls price
    $nasPrice = str_replace("$", "", $nasPrice);
    $nasPrice = str_replace(" ", "", $nasPrice);
     $jsonResponse = '{"price": "'. $nasPrice.'", "source": "1sturls"}';
     echo json_encode($jsonResponse);
    //return;

}



else{
    //could not get 1st or 2nd, so trying 3rdurl
    $yahResult = file_get_contents($yahoo);

$ticker = strtolower($ticker);
$yahArr1 = explode( 'id="yfs_l84_'.$ticker.'">', $yahResult);
if($yahArr1[1]){
   // echo $yahArr1[1];
$yahArr2 = explode( " ", $yahArr1[1]);
if($yahArr2[1]){
   
$yahPrice = $yahArr2[0];
}
}


if($yahPrice){
     $jsonResponse = '{"price": "'.floatval($yahPrice).'" , "source": "3rd"}';
     echo json_encode($jsonResponse);
    //return;

}

else{
      $jsonResponse = '{"error": "Y"Please make sure you passed a valid stock sticker symbol. (e.g. yoursite.com/?ticker=GOOG). If this error persists, please update this script with the latest version ( https://github.com/m140v/Real-time-Stock-Price-API/). The source site might have been reformatted."}';
     echo json_encode($jsonResponse);
    return;

}

}

}

}


else{
    $jsonResponse = '{"error": "please send a ticker symbol in your request with the key `ticker`."}';
     echo json_encode($jsonResponse);
    return;
    }





?>
