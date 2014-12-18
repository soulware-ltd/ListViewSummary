<?php

require_once('custom/include/summary.class.php');
$questionSummary = new listViewSummaryNumber();
$data['uids'] = explode(',', $_REQUEST['uid']);
$data['select_entire_list'] = $_REQUEST['select_entire_list'];
$html = '';
if (isset($_REQUEST['fields'])) {
    $fields = $_REQUEST['fields'];
    foreach ($fields as $field) {
        $array[] = $field['name'];
        $data['field_name'] = $field['name'];
        if ($field['type'] == 'int') {
            $summary[$field['index']]['value'] = $questionSummary->integerFormat($data);
            $summary[$field['index']]['align'] = $field['align'];
        }
        if ($field['type'] == 'float') {
            $summary[$field['index']]['value'] = $questionSummary->floatFormat($data);
            $summary[$field['index']]['align'] = $field['align'];
        }
        if ($field['type'] == 'currency') {
            $summary[$field['index']]['value'] = $questionSummary->currencyFormat($data);
            $summary[$field['index']]['align'] = $field['align'];
        }
    }

    $html = $questionSummary->htmlBuilder($summary, $_REQUEST['td_count']);
}
echo JSON::encode($html);

