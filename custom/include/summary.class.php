<?php

class listViewSummaryNumber {

    private function sugarSum($data) {
        $summary = 0;

        foreach ($data['uids'] as $id) {
            $bean = BeanFactory::getBean($_REQUEST['module'], $id);
            $summary+=$bean->$data['field_name'];
        }
        return $summary;
    }

    private function sugarSumAll($field) {
        $bean = BeanFactory::getBean($_REQUEST['module']);
        $data = $bean->get_full_list();
        $summary = 0;

        foreach ($data as $entity) {
            $summary += $entity->$field;
        }
        return $summary;
    }

    public function currencyFormat($data) {
        if ($data['select_entire_list'] == 0) {
            $summary = $this->sugarSum($data);
        } else {
            $summary = $this->sugarSumAll($data['field_name']);
        }
        $param = array('currency_id' => $GLOBALS['current_user']->getPreference('currency'));
        return currency_format_number($summary, $param);
    }

    public function integerFormat($data) {
        if ($data['select_entire_list'] == 0) {
            $summary = $this->sugarSum($data);
        } else {
            $summary = $this->sugarSumAll($data['field_name']);
        }
        return number_format($summary, 0, $GLOBALS['current_user']->getPreference('dec_sep')
                , $GLOBALS['current_user']->getPreference('num_grp_sep'));
    }

    public function floatFormat($data) {
        if ($data['select_entire_list'] == 0) {
            $summary = $this->sugarSum($data);
        } else {
            $summary = $this->sugarSumAll($data['field_name']);
        }
        return number_format($summary, $GLOBALS['current_user']->getPreference('default_currency_significant_digits'), $GLOBALS['current_user']->getPreference('dec_sep'), $GLOBALS['current_user']->getPreference('num_grp_sep'));
    }

    public function htmlBuilder($summary, $td_count) {
        $html = '';

        for ($i = 0; $i < $td_count; $i++) {

            if (!isset($summary[$i])) {
                $html.='<td></td>';
            } else {
                $html.='<td align=' . $summary[$i]['align'] . '>' . $summary[$i]['value'] . '</td>';
            }
        }
        $html.='<a name="summaryRows"></a>';
        return $html;
    }

}
