<?php


class listViewSummaryNumber {

    public function currencyFormat($data) {
        $summary = 0;

        foreach ($data['uids'] as $id) {
            $bean = BeanFactory::getBean($_REQUEST['module'], $id);
            $summary+=$bean->$data['field_name'];
        }
        $param = array('currency_id' => $GLOBALS['current_user']->getPreference('currency'));
        return currency_format_number($summary, $param);
    }

    public function integerFormat($data) {
        $summary = 0;

        foreach ($data['uids'] as $id) {
            $bean = BeanFactory::getBean($_REQUEST['module'], $id);
            $summary+=$bean->$data['field_name'];
        }
        return number_format($summary, 0, $GLOBALS['current_user']->getPreference('dec_sep')
                , $GLOBALS['current_user']->getPreference('num_grp_sep'));
    }

    public function floatFormat($data) {
        $summary = 0;

        foreach ($data['uids'] as $id) {
            $bean = BeanFactory::getBean($_REQUEST['module'], $id);
            $summary+=$bean->$data['field_name'];
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
        return $html;
    }

}
