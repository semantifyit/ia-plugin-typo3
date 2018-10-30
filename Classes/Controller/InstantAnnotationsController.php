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
           $pageRenderer->loadRequireJsModule('TYPO3/CMS/IaPluginTypo3/iasemantify-main', 'function(){
            var IA_injection_is_checked = false;
           var IA_semantify_url_route_js = "";

           var websiteUID = "Hkqtxgmkz";
           var websiteSecret = "ef0a64008d0490fc4764c2431ca4797b";

           iasi_saveWebsiteUID = "Hkqtxgmkz";
           iasi_saveWebsiteSecret = "ef0a64008d0490fc4764c2431ca4797b";

           startIA();
           }');

           $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
           parent::initializeView($view);


    }

  */
    /**
     * action main
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
        $this->view->assign('websiteUID', "Hkqtxgmkz");
        $this->view->assign('websiteSecret', "ef0a64008d0490fc4764c2431ca4797b");
    }

    /**
     * action frontend
     * @return void
     */
    public function frontendAction() {
        // TODO: delete this action, if not needed

        // $GLOBALS['TSFE'] could be accessed here
        $this->view->render();
    }

}