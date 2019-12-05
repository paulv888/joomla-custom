Refresh
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/cronjobs/process.php';
//echo "<pre>";
$origData = $formModel->getOrigData()[0];
//print_r($origData);
//echo $origData->{'pg_dexas___dexaId'};
echo '<script type="text/javascript">jQuery("#spinner").show();</script>';
if (empty($origData)) echo "empty";
$mysql = "DELETE FROM `pg_configurations` WHERE configurationId = '';";
PDOExec($mysql);
//        $mysql = "TRUNCATE `pg_configurations_details`;";
//        PDOExec($mysql);
$thiscommand = Array('callerID' => 264,'messagetypeID' => "MESS_TYPE_COMMAND", 'deviceID' => 264, 'commandID' => 2,'commandvalue' => $origData->{'pg_dexas___dexaId'});
$result = executeCommand($thiscommand);

//    echo "<pre>";
//    print_r($result);
//exit;
$dexasimported=0;
if (!array_key_exists('error',$result['SendCommand'][0])) {
  $dexaResponse = json_decode($result['SendCommand'][0]['result'][0], true);
//print_r($dexaResponse);
 //     echo "</pre>===GetDexas";
  $configs = $dexaResponse['data'];
  foreach ($configs as $config) {
    PDOupsert('pg_configurations', $config, array('configurationId' => $config['configurationId']));
    $dexasimported++;
  }
}

$feedback['result'] = $result;
$feedback['message'] = $dexasimported." Configurations upserted";
$feedback['show_result'] = false;
$result = RemoteKeys($feedback, $thiscommand);

//echo "</pre>";

if (array_key_exists('message',$result)) {
  JFactory::getApplication()->enqueueMessage($result['message']);
}

if (array_key_exists('error',$result)) {
  JFactory::getApplication()->enqueueMessage($result['error'], 'error');
//  $formModel->setFormErrorMsg("Error during update to AWS.");
  return false;
}


$new_link = str_replace("286", "284", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//echo $new_link;
//exit;
header("Location: ".$new_link);
echo '<script type="text/javascript">jQuery("#spinner").hide();</script>';

//jQuery(document).ready(function() {
//        console.log('end loaded')
 //});</script>



return;
?>
Save
<?php
// I am using params in my code
$saveParams = $params;
require_once $_SERVER['DOCUMENT_ROOT'].'/cronjobs/process.php';
//
$dexa = loadFields($fields['524'], $fieldMap, $data);

if (!empty($data['pg_dexas___dexaId'])) {   // Update
  $dexa['dexaId'] = $data['pg_dexas___dexaId'];
	$feedback['result'] = executeCommand(Array('callerID'=>326,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 326, 'commandID'=>7,'commandvalue' => $dexa['dexaId'], 'mess_text'=>json_encode($dexa)));
} else {  // post new one
	$feedback['result'] = executeCommand(Array('callerID'=>327,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 327, 'commandID'=>17, 'mess_text'=>json_encode($dexa)));
	if (array_key_exists('result',$feedback['result']['SendCommand']['0'])) {		// Get result Id
		$dexa['dexaId'] = $feedback['result']['SendCommand']['0']['result']['data']['dexaId'];
		$formModel->updateFormData('pg_dexas___dexaId', $dexa['dexaId'], true);
	}
}

if (array_key_exists('error',$feedback['result']['SendCommand']['0'])) {
    //JFactory::getApplication()->enqueueMessage($feedback['result']['SendCommand']['0']['error'], 'error');
    $formModel->setFormErrorMsg($feedback['result']['SendCommand']['0']['error']);
	  $params = $saveParams;
    return false;
}
if (DEBUG) print_r($dexa);

if (count($data['pg_configurations___configurationId'])>0) {   // We have some configs
    foreach ($data['pg_configurations___configurationId'] as $repeat=>$configId) {
		$config = Array();
    $config = loadFields($fields['525'], $fieldMap, $data, $repeat);
    if (defined('DEBUG')) print_r($config);
		$config['dexaId'] = $dexa['dexaId'];
    if (!empty($configId))  { // Update exsisting PUT
			if ($config['statusCode'] == "DESIGN") {
				$feedback['result']['Config PUT'.$repeat]  = executeCommand(Array('callerID'=>326,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 326, 'commandID'=>11,'commandvalue' => $dexa['dexaId']."|".$config['configurationId'] , 'mess_text'=>json_encode($config)));
			}
		} else {    // post new one
			$feedback['result']['Config PUT'.$repeat]  = executeCommand(Array('callerID'=>327,'messagetypeID'=>"MESS_TYPE_COMMAND",'deviceID' => 327, 'commandID'=>12,'commandvalue' =>$dexa['dexaId'], 'mess_text'=>json_encode($config)));
		}
	}
}

if (DEBUG) exit;
$saveForm = checkForErrors($formModel, $feedback);
$params = $saveParams;
return $saveForm;
?>