<?php

if(!isset($merge_config)) $merge_config = array();

//insert method = [append, prepend,replace]

$merge_config[] = array(
	'module' => 'application',
	'base_class' => 'ViewList',
	'sourcefile' => 'view.list.php',
	'type' => 'include/MVC/View/views',
	'method_name' => 'preDisplay',
	'insert_method' => 'append',
	'content' => '//ListViewSummary
                echo "<script type=\"text/javascript\" src=\"custom/themes/default/js/custom_summary_listview.js\"></script>";
                $this->lv->actionsMenuExtraItems[] = $this->buildMyMenuItem();
                //ListViewSummary
                ',);
$merge_config[] = array(
	'module' => 'application',
	'base_class' => 'ViewList',
	'sourcefile' => 'view.list.php',
	'type' => 'include/MVC/View/views',
	'method_name' => 'buildMyMenuItem',
        'method_remove'=> true,
        'method_visit'=> 'protected',
	'insert_method' => 'prepend',
	'content' => '
            //ListViewSummary
                global $app_strings;
                $html = "<a class=\"menuItem\" style=\"width: 150px;\" href=\"#\" onmouseover=\"hiliteItem(this,\"yes\");\"
                onmouseout=\"unhiliteItem(this);\" onclick=\"sugarListView.get_checks();
                if(sugarListView.get_checks_count() &lt; 1) {
                alert(\'{$app_strings[\'LBL_LISTVIEW_NO_SELECTED\']}\');
                return false;
                }
                document.MassUpdate.action.value=\'question_sum\';
                ajaxSummary();
                \">{$app_strings[\'LBL_SUMMARY_BUTTON\']}</a>";
                return $html;
            //ListViewSummary
        ',);
$merge_config[] = array(
	'module' => 'application',
	'base_class' => 'ViewList',
	'sourcefile' => 'view.list.php',
	'type' => 'include/MVC/View/views',
	'method_name' => 'listViewProcess',
	'insert_method' => 'replace',
        'original_content'=>'$this->lv->setup($this->seed, \'include/ListView/ListViewGeneric.tpl\', $this->where, $this->params);',
	'content' => '
            //ListViewSummary/n
             $this->lv->setup($this->seed, \'custom/include/ListView/ListViewGeneric.tpl\', $this->where, $this->params);
            //ListViewSummary
        ',);

