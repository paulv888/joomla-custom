<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

$org = $formModel->getOrigData()[0];  // Object

$changes = array();
$fields = [ 
  "mc_contracts___customerID",
  "mc_contracts___siteID",
  "mc_contracts___statusID",
  "mc_contracts___status_detailID",
  "mc_contracts___healthID",
  "mc_contracts___roadblockID",
  "mc_contracts___salesmen_employeeID",
  "mc_contracts___contract_number",
  "mc_contracts___contract_date",
  "mc_contracts___bill_advance",
  "mc_contracts___pm_employeeID"
];

//echo "<pre>";
//print_r($org);
//print_r($data);
//exit;

//echo "<pre>";

//$field = "mc_contracts___siteID";
//print_r($formModel->getElementData($field));
//echo strip_tags($data[$field]);
//exit;

//print_r($formModel->formData);

$user =& JFactory::getUser();
$user_id = $user->get('id');

foreach ($fields as $field) {
  $n = (is_array($formModel->getElementData($field,true)) ? $formModel->getElementData($field,true)[0] : $formModel->getElementData($field,true) );
  if ($field == "mc_contracts___contract_date") {
  	$n = ($n == "" ? null : substr($n, 0, 10));
  }
  $raw_name = $field.'_raw';
  if (($o = $org->{$raw_name}) != $n) {
      $changes[] = array('userID' => $user_id, 'contractID' => $formModel->getElementData("mc_contracts___id") ,'formID' => $formModel->getId(), 'fieldname' => $field, 
	 'old_value' => $org->{$field} ,  'new_value' => strip_tags($data[$field]), 'updatedate' => date("Y-m-d H:i:s"));
  }
}

//echo "<pre>";
//var_dump($changes);
//exit;

if (!empty($changes)) {
  // Get a db connection.
  $myDb = FabrikWorker::getDbo(false, 2);
  // Create a new query object.
  foreach ($changes as $change) {
    $columns = array();
    $values = array();
    $myQuery = $myDb->getQuery(true);
  // Insert columns.
    foreach ($change as $fieldname => $value) {
      $columns[] = $fieldname;
      // Insert values.
      $values[] = $value;
    }
  // Prepare the insert query.
    $myQuery
      ->insert($myDb->quoteName('mc_audit'))
      ->columns($myDb->quoteName($columns))
      ->values(implode(',', $myDb->quote($values)));
  // Reset the query using our newly populated query object.
  //print_r($columns);
  //print_r($values);
    $myDb->setQuery($myQuery);
    try {
    // Execute the query
      $result = $myDb->execute();
    //use $myDb->query() in Joomla2.5
    }
    catch (Exception $e) {
    // catch any database errors.
    }
  }

//$newid = (int)$myDb->insertid(); //get new record id
}

return;
