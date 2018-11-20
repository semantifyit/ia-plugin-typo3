<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;


/**
 * This class create frontend page address from the page id value and parameters.
 *
 * @author    Dmitry Dulepov <dmitry@typo3.org>
 * @package    TYPO3
 * @subpackage    tx_pagepath
 */
class tx_pagepath_resolver
{

    protected $pageId;
    protected $parameters;

    /**
     * Initializes the instance of this class.
     */
    public function __construct()
    {
        $params = json_decode(base64_decode(GeneralUtility::_GP('data')), true);
        if (is_array($params)) {
            $this->pageId = $params['id'];
            $this->parameters = $params['parameters'];
        }

        \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
    }

    /**
     * Handles incoming trackback requests
     *
     * @return    void
     */
    public function main()
    {
        header('Content-type: text/plain; charset=iso-8859-1');
        if ($this->pageId) {
            $this->createTSFE();

            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $typolinkConf = array(
                'parameter' => $this->pageId,
                'useCacheHash' => $this->parameters != '',
            );
            if ($this->parameters) {
                $typolinkConf['additionalParams'] = $this->parameters;
            }
            $url = $cObj->typoLink_URL($typolinkConf);
            if ($url == '') {
                $url = '/';
            }
            $parts = parse_url($url);
            if ($parts['host'] == '') {
                $url = GeneralUtility::locationHeaderUrl($url);
            }
            echo $url;
        }
    }

    /**
     * Initializes TSFE. This is necessary to have proper environment for typoLink.
     *
     * @return    void
     */
    protected function createTSFE()
    {
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(TypoScriptFrontendController::class, $GLOBALS['TYPO3_CONF_VARS'], $this->pageId, '');

        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        // Set linkVars, absRefPrefix, etc
        \TYPO3\CMS\Frontend\Page\PageGenerator::pagegenInit();
    }

}
//echo GeneralUtility::getIndpEnv('REMOTE_ADDR')." != ". $_SERVER['REMOTE_ADDR'];

if (GeneralUtility::getIndpEnv('REMOTE_ADDR') != $_SERVER['REMOTE_ADDR']) {

    header('HTTP/1.0 403 Access denied');
    // Empty output!!!
} else {

    $resolver = GeneralUtility::makeInstance(tx_pagepath_resolver::class);
    $resolver->main();
}
