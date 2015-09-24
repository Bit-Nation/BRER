<?php
error_log("original message:".$_POST['value'], 0);
$message=base64_encode($_POST['value']);
error_log("original message:".$message, 0);
$a = shell_exec("nodejs serverb62f52d67a7fc1aa598dc32a9d9ea43d37cd0d9432e1ff7de8758444acccf632.js ".$message);
error_log("message to hash:".$a, 0);
$a = str_replace("\n", '', $a);
$a = str_replace("\r", '', $a);
$strHash = hash('sha256', $a);
//error_log("resulting hash:".$strHash, 0);
include '../horizon-settings.php';
$data = array(
    'requestType'=>'sendMessage',
    'recipient'=>$recipient,
    'secretPhrase'=>$secretPhrase,
    'feeNQT'=>$feeNQT,
    'deadline'=>$deadline,
    'message'=>'BE-ID hash: '.$strHash
);
$data_string = '';
foreach($data as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
rtrim($data_string, '&');
$ch = curl_init('https://eu1.woll-e.net:8443/nhz');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686; rv:20.0) Gecko/20121230 Firefox/20.0');                                                                                                                                                                     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                                                                                                                                                                                
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                                                                                                                               
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                                                                                                                                                                                                                               
curl_setopt($ch, CURLOPT_POST, count($data));                                                                                                                                                                                                                                  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                                                                                                                                                                            
$result = curl_exec($ch);                                                                                                                                                                                                                                                      
$error = curl_error($ch);                                                                                                                                                                                                                                                      
curl_close($ch);                                                                                                                                                                                                                                                               
if(!empty($error))                                                                                                                                                                                                                                                             
{                                                                                                                                                                                                                                                                              
    return $error;                                                                                                                                                                                                                                                             
}                                                                                                                                                                                                                                                                              
#var_dump(json_decode($result));
die(json_encode(array(
  'code' => $a,
  'hz_id' => $result,
  'hash' => $strHash
)));
