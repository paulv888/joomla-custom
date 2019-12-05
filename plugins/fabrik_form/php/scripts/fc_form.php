<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// echo "<pre>";
// print_r($formModel->getOrigData()[0]);
// print_r($data);

if ($data['projectlist___phase'] == 'Complete' && $formModel->getOrigData()[0]->{'projectlist___phase'} != 900 ) {
// echo "Completing";
	$formModel->updateFormData('projectlist___status', 1, true);
	$formModel->updateFormData('projectlist___completedate', date('Y-m-d H:i:s'), true);
} else {		// Not send in so reset to Active
	$formModel->updateFormData('projectlist___status', 0, true);
}
// print_r($data);
// echo "</pre>";

/*Object
(
    [projectlist___id] => 600
    [projectlist___id_raw] => 600
    [projectlist___project] => 2
    [projectlist___project_raw] => 2
    [projectlist___phase] => 300
    [projectlist___phase_raw] => 300
    [projectlist___status] => 0
    [projectlist___status_raw] => 0
    [slug] => 600
    [__pk_val] => 600
)
Array
(
    [projectlist___id] => 600
    [projectlist___project] => 2
    [projectlist___phase] => Complete
    [listid] => 289
    [listref] => 289
    [rowid] => 600
    [Itemid] => 656
    [option] => com_fabrik
    [task] => form.process
    [formid] => 276
    [returntoform] => 0
    [fabrik_ajax] => 1
    [package] => fabrik
    [format] => raw
    [Submit] => 
    [projectlist___id_raw] => 600
    [projectlist___project_raw] => 2
    [projectlist___phase_raw] => Array
        (
            [0] => 900
        )

    [projectlist___status_raw] => 
    [__pk_val] => 600
    [view] => form
    [id] => 0
    [projectlist___status] => 
)*/
?>
