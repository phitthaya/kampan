<?php
$strAccessToken = "JlTAZvIGV5XmwFLezTAvI6gscguh/8076faOq4K4+7USnOCZKEJVGrWDT9Lphce2Bchr3EPDgg1MpVNepops6r+F6hf1SYEk3Hs3ddTR/TbvA/wMppKeDWJCWPza9YmtyrcCU79j2okYD3rS1ybbhQdB04t89/1O/w1cDnyilFU=";
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$strUrl = "https://api.line.me/v2/bot/message/reply";
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$_msg = $arrJson['events'][0]['message']['text'];
$api_key="41FEuTQ4Z0n06IeNqJVPsJ1FX37h_oVc";
$url = 'https://api.mlab.com/api/1/databases/kampan/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/kampan/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
$data = json_decode($json);
$isData=sizeof($data);
if (strpos($_msg, 'สอนกำปั้น') !== false) {
  if (strpos($_msg, 'สอนกำปั้น') !== false) {
    $x_tra = str_replace("สอนกำปั้น","", $_msg);
    $pieces = explode("|", $x_tra);
    $_question=str_replace("[","",$pieces[0]);
    $_answer=str_replace("]","",$pieces[1]);
    //Post New Data
    $newData = json_encode(
      array(
        'question' => $_question,
        'answer'=> $_answer
      )
    );
    $opts = array(
      'http' => array(
          'method' => "POST",
          'header' => "Content-type: application/json",
          'content' => $newData
       )
    );
    $context = stream_context_create($opts);
    $returnValue = file_get_contents($url,false,$context);
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'ขอบคุณที่สอน กำปั้น น๊าน่ารักที่สุด';
  }
}else{
  if($isData >0){
   foreach($data as $rec){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $rec->answer;
   }
  }else{
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'ตะเองเค้าไม่รู้คำตอบอ่ะ ตัวเองสอนให้เค้าได้นะเพียงพิมพ์: สอน กำปั้น[คำถาม|คำตอบ]พิมพ์ให้ถูกต้องนะตัวเอง';
  }
}
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL,$strUrl);
curl_setopt($channel, CURLOPT_HEADER, false);
curl_setopt($channel, CURLOPT_POST, true);
curl_setopt($channel, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($channel, CURLOPT_RETURNTRANSFER,true);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($channel);
curl_close ($channel);
?>
