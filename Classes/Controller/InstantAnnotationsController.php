<?php

namespace STI\IaPluginTypo3\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Utility\DebugUtility;


/**
 * InstantAnnotationsController
 */
class InstantAnnotationsController extends ActionController
{
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

        $this->view->assign('websiteApiKey', $websiteApiKey);
        $this->view->assign('websiteApiSecret', $websiteApiSecret);
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


        //$limit = GeneralUtility::_GP('action');

        $result = "nice";
        echo "Yes, finaly you reached the controller!";
        //$ajaxObj->addContent('success', $result); // In JS 'success' is the final result passed from here
        //$ajaxObj->setContentFormat('json');       // Writing back as JSON array
    }
}