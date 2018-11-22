<?php

// HOOK is called after caching
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'EXT:' . $_EXTKEY . '/Classes/class.tx_annotation_input.php:&tx_annotation_input->performNotCached';

// HOOK is called before caching
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'EXT:'. $_EXTKEY .'/Classes/class.tx_annotation_input.php:&tx_annotation_input->performCached';

//page path
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Vendor/typo3-pagepath/class.tx_pagepath_resolver.php';