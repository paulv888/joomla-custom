Save
<?php
// I am using params in my code
$saveParams = $params;
define( 'GROUP_ID1', 526 );
define( 'GROUP_ID2', 527 );
require_once $_SERVER['DOCUMENT_ROOT'].'/cronjobs/process.php';
//
if (DEBUG) echo "<pre>";
$config = loadFields($fields[GROUP_ID1], $fieldMap, $data);
$config['dexaId'] = (!empty($data['pg_configurations___dexaId_raw']['0']) ? $data['pg_configurations___dexaId_raw']['0'] : $formModel->getOrigData()[0]->{'pg_configurations___dexaId_raw'}); 


if (!empty($data['pg_configurations___configurationId']) && !(array_key_exists('Copy',$data))) {   // Update and Always post on Save As
  $config['configurationId'] = $data['pg_configurations___configurationId'];
  $feedback['result']['Config PUT'] = executeCommand(Array('callerID'=>326,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 326, 'commandID'=>11,'commandvalue' => $config['dexaId']."|".$config['configurationId'], 'mess_text'=>json_encode($config)));
} else {  // post new one
  $feedback['result']  = executeCommand(Array('callerID'=>327,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 327, 'commandID'=>12,'commandvalue' =>$config['dexaId'], 'mess_text'=>json_encode($config)));
  if (array_key_exists('result',$feedback['result']['SendCommand']['0'])) {    // Get result Id
		$config['configurationId'] = $feedback['result']['SendCommand']['0']['result']['data']['configurationId'];
		$formModel->updateFormData('pg_configurations___configurationId', $config['configurationId'], true);
	}
}

if (DEBUG) print_r($config);
//var_dump($feedback['result']['SendCommand']['0']['error']);
if (array_key_exists('error',$feedback['result']['SendCommand']['0'])) {
    //JFactory::getApplication()->enqueueMessage($feedback['result']['SendCommand']['0']['error'], 'error');
    $formModel->setFormErrorMsg($feedback['result']['SendCommand']['0']['error']);
	  $params = $saveParams;
    return false;
}
if (DEBUG) print_r($dexa);


if (count($data[pg_configurations_details___id])>0) {  // We have some config details
    foreach ($data[pg_configurations_details___id] as $repeat=>$detailId) {
      $detail = Array();
      $detail = loadFields($fields[GROUP_ID2], $fieldMap, $data, $repeat);
      $detail['configurationId'] = $config['configurationId'];
      if (DEBUG)  print_r($detail);
      if ($config['statusCode'] == "DESIGN") {
        if (!empty($detailId) && !(array_key_exists('Copy',$data)) )  { // Update exsisting PUT  (but always post copy)
            $feedback['result']['Detail PUT'.$repeat]  = executeCommand(Array('callerID'=>326,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 326, 'commandID'=>16,'commandvalue' =>$config['dexaId']."|".$config['configurationId']."|".$detail['configurationDetailId'], 'mess_text'=>json_encode($detail)));
        } else {    // post new one
          $feedback['result']['Detail POST'.$repeat]  = executeCommand(Array('callerID'=>327,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 327, 'commandID'=>15,'commandvalue' =>$config['dexaId']."|".$config['configurationId'], 'mess_text'=>json_encode($detail)));
        }
      }
    }
}

if (DEBUG) exit;
$saveForm = checkForErrors($formModel, $feedback);
$params = $saveParams;
return $saveForm;

?>
New Entry
<?php
$formModel->data['pg_configurations___configurationId'] = "";
$formModel->data['pg_configurations___lastUpdateDateTime'] = "";
$formModel->data['pg_configurations___lastUpdateAuthor'] = "";
$formModel->data['pg_configurations___statusCode'] = "DESIGN";
$formModel->data['pg_configurations___version'] = 0;
$formModel->data['pg_configurations___creationDateTime'] = "";
$formModel->data['pg_configurations___creationAuthor'] = "";
if (is_array($formModel->data['pg_configurations_details___configurationId'])) foreach ($formModel->data['pg_configurations_details___configurationId'] as $key=>$value) {
  $formModel->data['pg_configurations_details___configurationId'][$key] = "";
}
if (is_array($formModel->data['pg_configurations_details___configurationDetailId'])) foreach ($formModel->data['pg_configurations_details___configurationDetailId'] as $key=>$value) {
  $formModel->data['pg_configurations_details___configurationDetailId'][$key] = "";
}
?>
Refresh
<?php
echo '<script type="text/javascript">jQuery("#spinner").show();</script>';
$dexaId = (!empty($data['pg_configurations___dexaId_raw']['0']) ? $data['pg_configurations___dexaId_raw']['0'] : $formModel->getOrigData()[0]->{'pg_configurations___dexaId_raw'}); 
$saveParams = $params;
require_once $_SERVER['DOCUMENT_ROOT'].'/cronjobs/process.php';
//echo "<pre>";
$origData = $formModel->getOrigData()[0];
//print_r($origData);

//echo $origData->{'pg_dexas___dexaId'};
//exit;
$mysql = "DELETE FROM `pg_configurations_details` WHERE configurationDetailId = '';";
PDOExec($mysql);
//        $mysql = "TRUNCATE `pg_configurations_details`;";
//        PDOExec($mysql);
//echo $dexaId;

$thiscommand = Array('callerID' => 264,'messagetypeID' => "MESS_TYPE_COMMAND", 'deviceID' => 264, 'commandID' => 3,'commandvalue' => $dexaId."|".$origData->{'pg_configurations___configurationId'});
$result = executeCommand($thiscommand);
//print_r($result);

$dexasimported=0;
if (!array_key_exists('error',$result['SendCommand'][0])) {
  $dexaResponse = json_decode($result['SendCommand'][0]['result'][0], true);
//print_r($dexaResponse);
 //     echo "</pre>===GetDexas";
  $configs = $dexaResponse['data'];
  foreach ($configs as $config) {
    PDOupsert('pg_configurations_details', $config, array('configurationDetailId' => $config['configurationDetailId']));
    $dexasimported++;
  }
}

$feedback['result'] = $result;
$feedback['message'] = $dexasimported." Configuration Details upserted";
$feedback['show_result'] = false;
$result = RemoteKeys($feedback, $thiscommand);

//echo "</pre>";

if (array_key_exists('message',$result)) {
  JFactory::getApplication()->enqueueMessage($result['message']);
}

if (array_key_exists('error',$result)) {
  JFactory::getApplication()->enqueueMessage($result['error'], 'error');
    $params = $saveParams;
    return false;
}


header("Location: ".str_replace("287", "285", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
echo '<script type="text/javascript">jQuery("#spinner").hide();</script>';
$params = $saveParams;
return true;
?>