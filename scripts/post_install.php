<?php

function post_install() {

    require_once(__DIR__ . '/../vendor/autoload.php');
    require_once(__DIR__ . '/merge.config.php');
  
    $view_merge = new Soulware\EditViewOnInstall\viewMerge();
    $view_merge->setMergeConfig($merge_configs);
    $view_merge->install();
}

?>