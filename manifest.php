<?php

$manifest = array(
	'acceptable_sugar_versions' => array (
		'regex_matches' => array (
			0 => "5.0.*",
			1 => "5.1.*",
			2 => "5.2.*",
			3 => "5.5.*",
			4 => "6.4.*",
			5 => "6.5.*",
		),
	),
	'acceptable_sugar_flavors' => array (
		0 => 'CE',
	),
	'name' 				=> 'SoulwareListViewSummary',
	'description' 		=> "Adds an option to LisView Mass Update to sum selected records' number types fields. Not upgrade safe, please read the readme file before install.",
	'author' 			=> 'Péter Spendel, Soulware Ltd.',
	'published_date'	=> '2014/12/05',
	'version' 			=> '1.0.0',
	'type' 				=> 'module',
	'icon' 				=> '',
	'is_uninstallable' => true,
);
$installdefs = array(
	'id'=> 'SoulwareListViewSummary',
	'copy' => array(
		0 => array(
		'from' => '<basepath>/custom/themes/default/js/custom_summary_listview.js',
		'to' => 'custom/themes/default/js/custom_summary_listview.js',
		),
		1 => array(
		'from' => '<basepath>/custom/include/ListView/ListViewGeneric.tpl',
		'to' => 'custom/include/ListView/ListViewGeneric.tpl',
		),
		2 => array(
		'from' => '<basepath>/custom/include/summary.class.php',
		'to' => 'custom/include/summary.class.php',
		),
		3 => array(
		'from' => '<basepath>/custom/Extension/application/Ext/EntryPointRegistry/ListViewSummary.php',
		'to' => 'custom/Extension/application/Ext/EntryPointRegistry/ListViewSummary.php',
		),
                4 => array(
                    'from'=>'<basepath>/ListViewSummary.php',
                    'to'=>'ListViewSummary.php',
                ),
	),
        'language'=> array (
            
            array(
                'from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.lang.php',
                'to_module' => 'application',
                'language' => 'en_us',
            ),
            array(
                'from' => '<basepath>/custom/Extension/application/Ext/Language/hu_hu.lang.php',
                'to_module' => 'application',
                'language' => 'hu_hu',  
            ),
        ),
        'post_uninstall' => array(
	        '<basepath>/scripts/post_uninstall.php',
	    ),
);

?>
