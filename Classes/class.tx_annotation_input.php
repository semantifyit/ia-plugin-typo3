<?php

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \STI\IaPluginTypo3\Domain\Model\SemantifyItWrapper;


class tx_annotation_input
{
    protected $sqlFROM = 'pages';
    protected $sqlSELECT = 'iasemantify_ann_id';

    function performNotCached(&$params, &$that)
    {
        if (!$GLOBALS['TSFE']->isINTincScript()) {
            return;
        }
        $this->main($params, $that);
    }

    function performCached(&$params, &$that)
    {
        if ($GLOBALS['TSFE']->isINTincScript()) {
            return;
        }
        $this->main($params, $that);
    }

    /**
     * performs the main injector task (reading database -> get json from semantify.it -> inject)
     *
     * @param $params object
     * @param $that   object not used at the moment
     */
    function main(&$params, &$that)
    {
        $currentPageId = $GLOBALS['TSFE']->id;


        if ($GLOBALS['TSFE']->sys_language_uid != 0) {
            $this->sqlFROM = "pages_language_overlay";

            //read from database
            $dbEntries = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                $this->sqlSELECT,
                $this->sqlFROM,
                'pid = ' . $currentPageId . ' AND sys_language_uid = ' . $GLOBALS['TSFE']->sys_language_uid
            );
        } else {

            $dbEntries = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                $this->sqlSELECT,
                $this->sqlFROM,
                'uid = ' . $currentPageId
            );
        }


        //check entries
        if (!isset($dbEntries) || $GLOBALS['TYPO3_DB']->sql_num_rows($dbEntries) == 0) {
            return;
        } else {

            //starting wrapper
            $Semantify = new SemantifyItWrapper();
            $anno_ids = "";

            //get annotations from the database
            foreach ($dbEntries as $res) {
                $anno_ids = $res[$this->sqlSELECT];
                break;
            }

            //option for automatic annotaiton search
            $confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ia_plugin_typo3']);
            $annotationByURL = $confArray['smtf.']['annotationByURL'];

            //option for automatic annotaiton search insertion
            if($annotationByURL==1) {
                $url = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
                $annotation = $Semantify->getAnnotationByURL($url);

                if (($annotation != "0")
                    && ($annotation !== false)
                    && ($annotation != "")
                    && ($annotation != '{"message":"Entry not found"}')) {
                    $this->addAnnotation($params['pObj']->content, $annotation);
                }
            }
            //jisSBXhDf
            //e72160320929c30602a07a19deffb025

            if (!(($anno_ids == "0") || ($anno_ids == ""))) {

                $fullAnnIdsArr = explode(",", $anno_ids);
                array_shift($fullAnnIdsArr);       //remove first element

                foreach ($fullAnnIdsArr as $value) {
                    $annId = explode(";", $value)[0];
                    try {
                        $annotation = $Semantify->getAnnotation($annId);
                        //if it is field not empty or with 0
                        if (($annotation != "0") && ($annotation !== false) && ($annotation != "")) {
                            $this->addAnnotation($params['pObj']->content, $annotation, $annId);
                        }

                    } catch (Exception $e) {
                    }
                }

            }


        }
    }

    /**
     * this function takes a pointer to the actual html content of the page rendered. It injects the string inside $codeToInject before the closing head tag
     *
     * @param $content      string the actual html content rendered
     * @param $codeToInject string to inject
     */
    private function addAnnotation(&$content, $annotation, $id)
    {
        if (strlen($annotation) == 0) {
            return;
        }

        $semantify_text = '<!-- Great, right? Created by the instant annotator of semantify.it ('.$id.') -->
            ';


        $content = str_replace("</head>",
                               $semantify_text . '<script type="application/ld+json">' . $annotation . '</script>' . '</head>',
                               $content);
    }

    private function iasemantify_printJson($json, $current_url, $id)
    {
        $out = '<!-- Created by the instant annotator of semantify.it ('.$id.') -->';
        $out .= '<script type="application/ld+json">';
        $out .= $json;
        $out .= '</script>';
        echo $out;
        $json = json_encode($json);
        global $IA_foundURL;
        if (strpos($json, $current_url) !== false) {
            $IA_foundURL = 'true';
        }
    }
}

