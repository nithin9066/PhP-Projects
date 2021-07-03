<?php

function send($n,$m)
{
          
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2?authorization=Paste here your key&sender_id=TXTIND&message=".urlencode($m)."&route=v3&numbers=".urlencode($n),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
 curl_close($curl);
    
    if ($err) {
      echo "<script>alert('Error! Unable to send sms')</script>";
    } else {
        echo "<script>alert('Successfully send sms $n')</script>";
    }
}

?>