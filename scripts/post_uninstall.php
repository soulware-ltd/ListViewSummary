<?php

	//for uninstall script there is no need for wrapper method
    require_once(__DIR__ . '/../vendor/autoload.php');
    require_once(__DIR__ . '/merge.config.php');
    $view_merge = new Soulware\EditViewOnInstall\viewMerge();
    $view_merge->setMergeConfig($merge_configs);
    $view_merge->uninstall();

?>
