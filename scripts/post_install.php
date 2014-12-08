<?php

function post_install() {

    require_once(__DIR__ . '/view_merge.class.php');

    $view_merge = new Soulware\viewMerge();
    $view_merge->install();
}

?>