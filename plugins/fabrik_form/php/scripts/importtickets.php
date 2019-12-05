<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

set_time_limit(0);

echo "<pre>";

//echo  $this->data['mc_import_mongoDB___purge_raw'][0];

//if ($this->data['mc_import_mongoDB___purge_raw'][0]==0) {
//	$db = JFactory::getDbo();
//	$query = $db->getQuery(true);
	// delete all custom keys for user 1001.
//	$query->delete($db->quoteName('mongoDB_users'));
//	$query->where($db->quoteName('period').' = '.$db->quote(date("Y-m-d",strtotime($this->data['mc_import_mongoDB___period_raw']))));
//	$db->setQuery($query);
//	$result = $db->execute();
//}

//print_r($this->data);
//echo $this->data['mc_import_mongoDB___period_raw'].'</br>';
//echo $this->data['mc_import_mongoDB___filename_raw'].'</br>';

$file = $this->data['mc_import_mongoDB_tickets___filename_raw'];
//echo $file;
if (!($data = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file))) JFactory::getApplication()->enqueueMessage("Error Reading file");
echo $data.'</br>';
//$data = preg_replace('~\R~u', ",", $data);
//$data = '['.rtrim($data, ",").']';

$data = preg_replace('!/\*.*?\*/!s', '', $data);

$mess = "";
$dataArray = json_decode($data,true);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
        break;
        case JSON_ERROR_DEPTH:
            $mess = ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $mess = ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $mess = ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $mess = ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $mess = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $mess = ' - Unknown error';
        break;
    }
if (!empty($mess)) JFactory::getApplication()->enqueueMessage($mess);

// Get a db connection.
$db = FabrikWorker::getDbo(false, 2);

$count = 0;

$dataArray = $dataArray['result'];

//echo "<pre>";
//print_r($dataArray);
//exit; 
 

foreach ($dataArray as $key => $ticket) {
//	unset($dataArray[$key]['migrated']);


//	echo "$key $user".'</br>';
//	print_r($dataArray[$key]);
	$dataArray[$key]['siteID'] =  $this->data['mc_import_mongoDB_tickets___siteID'];
	$dataArray[$key]['importdate'] = date("Y-m-d H:i:s",time());
	$dataArray[$key]['main_productID'] = 4;
	if ($ticket['ticketType']  == "Concrete") {
		$dataArray[$key]['user_type'] = 50;
	} elseif ($ticket['ticketType']  == "Aggregate") {
		$dataArray[$key]['user_type'] = 51;
	} elseif ($ticket['ticketType']  == "Pump") {
		$dataArray[$key]['user_type'] = 52;
	} elseif ($ticket['ticketType']  == "Extra Products") {
		$dataArray[$key]['user_type'] = 59;
	} else {
		JFactory::getApplication()->enqueueMessage("Unknown product code found::".$ticket['ticketType'].'::');
	}
	unset($dataArray[$key]['ticketType']);

	// Insert columns.
	$columns = array_keys($dataArray[$key]);
	// Insert values.
	$values = array_values($dataArray[$key]);

	// Create a new query object.


//	print_r($columns);
//	print_r($values);

	foreach($values as $vk => $value) {
		$values[$vk] = $db->quote($value);
	} 

	$query = $db->getQuery(true);

	// Prepare the insert query.
	$query
		->insert($db->quoteName('mongoDB_tickets'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
	 
	// Set the query using our newly populated query object and execute it.
	$db->setQuery($query);
	$db->execute();
	$count++;
 

}


JFactory::getApplication()->enqueueMessage("$count Records imported.");
//print_r($dataArray);

//echo "</pre>";
return;
