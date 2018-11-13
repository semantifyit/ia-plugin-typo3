<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'STI.' . $_EXTKEY,
    'IaPluginTypo3',
    'IaPluginTypo3'
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'STI.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'InstantAnnotations',	// Submodule key
		'',						// Position
		array(
			'InstantAnnotations' => 'main, uploadFile, saveForm, listPages, deleteEntry, ajaxHandler',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.png',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_ia.xlf',
		)
	);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
        'InstantAnnotations::ajaxHandlerAction',
        'STI\\IaPluginTypo3\\Controller\\InstantAnnotations->ajaxHandlerAction'
    );
}
