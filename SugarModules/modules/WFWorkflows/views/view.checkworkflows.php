<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */
require_once('include/TemplateHandler/TemplateHandler.php');
require_once('custom/include/Workflow/WFManager.php');

class WFWorkflowsViewCheckWorkflows extends SugarView
{
    public $type = 'checkworkflows';
    public $showTitle = true;
    public $view = 'CheckWorkflows';
    private $th;

    function preDisplay() {
        $this->th = new TemplateHandler();
        $this->th->ss =& $this->ss;
        $this->tpl = get_custom_file_if_exists('modules/WFWorkflows/tpls/CheckWorkflows.tpl');
    }

    function display()
    {
        $this->ss->assign('module', 'WFWorkflows');
        $this->ss->assign('checkResults', WFManager::checkWorkflows());
        echo $this->getModuleTitle($this->showTitle);
        $this->ss->load_filter('output', 'trimwhitespace');
        echo $this->th->displayTemplate($this->module, $this->view, $this->tpl);
    }

    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;
        $params = array($this->_getModuleTitleListParam($browserTitle));
        $params[] = $mod_strings['LBL_CHECK_WORKFLOWS'];
        return $params;
    }
}
