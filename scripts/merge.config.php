<?php

    $merge_configs=array();   
    $view_config= new Soulware\EditViewOnInstall\viewMergeConfig('application','view.list.php','include/MVC/View/views','ViewList','preDisplay','append','
                //ListViewSummary
                echo "<script type=\"text/javascript\" src=\"custom/themes/default/js/custom_summary_listview.js\"></script>";
                $this->lv->actionsMenuExtraItems[] = $this->buildMyMenuItem();
                //ListViewSummary');
    
    $merge_configs[]=$view_config;
    
    $view_config= new Soulware\EditViewOnInstall\viewMergeConfig('application','view.list.php','include/MVC/View/views','ViewList','buildMyMenuItem','prepend',
                '//ListViewSummary
                global $app_strings;
                $html = "<a class=\"menuItem\" style=\"width: 150px;\" href=\"#\" onmouseover=\"hiliteItem(this,\'yes\');\"
                onmouseout=\"unhiliteItem(this);\" onclick=\"sugarListView.get_checks();
                if(sugarListView.get_checks_count() &lt; 1) {
                alert(\'{$app_strings[\'LBL_LISTVIEW_NO_SELECTED\']}\');
                return false;
                }
                document.MassUpdate.action.value=\'question_sum\';
                ajaxSummary();
                \">{$app_strings[\'LBL_SUMMARY_BUTTON\']}</a>";
                return $html;
                //ListViewSummary'); 
    $view_config->method_remove=true;
    $view_config->method_visit='protected';
    
    $merge_configs[]=$view_config;
    
    $view_config= new Soulware\EditViewOnInstall\viewMergeConfig('application','view.list.php','include/MVC/View/views','ViewList','listViewProcess','replace','
                //ListViewSummary/n
                $this->lv->setup($this->seed, \'custom/include/ListView/ListViewGeneric.tpl\', $this->where, $this->params);
                //ListViewSummary');
    $view_config->original_content='$this->lv->setup($this->seed, \'include/ListView/ListViewGeneric.tpl\', $this->where, $this->params);';
    $merge_configs[]=$view_config;

