<?php

namespace STI\IaPluginTypo3\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\DebugUtility;


/**
 * InstantAnnotationsController
 */
class InstantAnnotationsController extends ActionController
{

    protected $sqlFROM = 'pages';
    protected $sqlSELECT = 'iasemantify_ann_id';

    /*

       protected $defaultViewObjectName = \TYPO3\CMS\Backend\View\BackendTemplateView::class;


       protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
       {


           $pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/moment');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/jquery.min');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/bootstrap');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/datetimepicker');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/snackbar');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/iasemantify-admin');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/iasemantify-login');
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/instantAnnotations');
           //$pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/iasemantify-main', '');

           $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
           parent::initializeView($view);


}
*/

    /**
     * action main
     *
     * @return void
     */
    public function mainAction()
    {


        /*
        $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            ''                                              // WHERE
        );
        $page_names_query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'inject_file_name',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            '',
            'inject_file_name'
        );
        $page_names = array();
        foreach($page_names_query as $elem) {
            array_push($page_names, $elem[inject_file_name]);
        }
        */

        $id = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        $this->view->assign('pageId', $id);

        $confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ia_plugin_typo3']);

        $websiteApiKey = $confArray['ia.']['WebsiteApiKey'];
        $websiteApiSecret = $confArray['ia.']['WebsiteApiSecret'];

        $savedAnnotations = $this->get_post_meta($id);

        $this->view->assign('websiteApiKey', $websiteApiKey);
        $this->view->assign('websiteApiSecret', $websiteApiSecret);
        $this->view->assign('savedAnnotations', $savedAnnotations);
        $this->view->render();
    }

    /**
     * action frontend
     *
     * @return void
     */
    public function frontendAction()
    {
        // TODO: delete this action, if not needed


        // $GLOBALS['TSFE'] could be accessed here
        $this->view->render();
    }


    /**
     * Renders the table as pagination occurs
     *
     * @param array                                   $params  Array of parameters from the AJAX interface, currently unused
     * @param \TYPO3\CMS\Core\Http\AjaxRequestHandler $ajaxObj Object of type AjaxRequestHandler
     * @return void
     */
    public function ajaxHandlerAction($params = array())
    {
        $action = GeneralUtility::_GP('action');
        $ds_hash = GeneralUtility::_GP('ds_hash');
        $id = (int)GeneralUtility::_GP('id');


        switch($action){
            case "iasemantify_push_ann":
               $out[] = $this->iasemantify_push_ann();
            break;

            case "iasemantify_multi_push_ann":
                $out[] = $this->iasemantify_multi_push_ann();
                break;


            case "iasemantify_delete_ann":
                $out[] = $this->iasemantify_delete_ann();
                break;
        }



        $arguments = $this->request->getArguments();

        //$ajaxObj = $GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX'];
        //$limit = GeneralUtility::_GP('action');

        //$result = "nice";
        //var_dump($_POST);
        //echo "Yes, finaly you reached the controller!";
        //$ajaxObj->addContent('action'); // In JS 'success' is the final result passed from here

        //$ajaxObj->setContentFormat('json');       // Writing back as JSON array

        $out[] = $_POST;

        // Process your POSt data here

        header('Content-Type: application/json');
        return json_encode($out);
    }





    /**
     * trying to simulate wordpress get post meta in typo3
     */
     private function get_post_meta($pageId){

         $aditional = "";

         if ($GLOBALS['TSFE']->sys_language_uid != 0) {
            $this->sqlFROM = "pages_language_overlay";
            $aditional = ' AND sys_language_uid = ' . $GLOBALS['TSFE']->sys_language_uid;
        }

         $dbEntries = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
             $this->sqlSELECT,
             $this->sqlFROM,
             'uid = ' . $pageId . $aditional
         );


         if (!isset($dbEntries) || $GLOBALS['TYPO3_DB']->sql_num_rows($dbEntries) == 0) {
             return false;
         } else {

             //get annotations from the database
             foreach ($dbEntries as $res) {
                 $result = $res[$this->sqlSELECT];
                 break;
             }

             return $result;
         }
    }


    private function update_post_meta($pageId, $updateArray){

        $aditional = "";

        if ($GLOBALS['TSFE']->sys_language_uid != 0) {
            $this->sqlFROM = "pages_language_overlay";
            $aditional = ' AND sys_language_uid = ' . $GLOBALS['TSFE']->sys_language_uid;
        }

        $GLOBALS['TYPO3_DB']->exec_UPDATEquery (
            $this->sqlFROM,
            'uid = ' . $pageId . $aditional,
            $updateArray,
            false);

    }




     private function iasemantify_push_ann(){

            $id = (int)GeneralUtility::_GP('id');

            $previousAnnotations = $this->get_post_meta($id);

            $newAnnotations = $previousAnnotations . "," . GeneralUtility::_GP("ann_id") . ';' . GeneralUtility::_GP("ds_hash") . ';' . GeneralUtility::_GP("web_id") . ';' . GeneralUtility::_GP("web_secret");

            $this->update_post_meta($id, array($this->sqlSELECT => $newAnnotations));

            return $newAnnotations;

    }

    private function iasemantify_multi_push_ann(){

        $id = (int)GeneralUtility::_GP('id');

        $previousAnnotations = $this->get_post_meta($id);

        $ann_ids = explode(",", GeneralUtility::_GP("ann_ids"));
        $ds_hashes = explode(",", GeneralUtility::_GP("ds_hashes"));


        $newAnnotations = $previousAnnotations;

        for ($i = 0; $i < count($ann_ids); $i++) {
            $newAnnotations = $newAnnotations . "," . $ann_ids[$i] . ';' . $ds_hashes[$i] . ';' . GeneralUtility::_GP("web_id") . ';' . GeneralUtility::_GP("web_secret");
        }

        $this->update_post_meta($id, array($this->sqlSELECT => $newAnnotations));

        return $newAnnotations;

    }

    private function iasemantify_delete_ann(){

        $id = (int)GeneralUtility::_GP('id');

        $previousAnnotations = $this->get_post_meta($id);

        $newAnnotations = str_replace("," . GeneralUtility::_GP("ann_id") . ';' . GeneralUtility::_GP("ds_hash") . ';' . GeneralUtility::_GP("web_id") . ';' . GeneralUtility::_GP("web_secret"), "", $previousAnnotations);

        $this->update_post_meta($id, array($this->sqlSELECT => $newAnnotations));

        return $newAnnotations;

    }


    private function iasemantify_reset_ann(){

        $id = (int)GeneralUtility::_GP('id');

        $newAnnotations = "";

        $this->update_post_meta($id, array($this->sqlSELECT => $newAnnotations));

        return $newAnnotations;

    }



}

