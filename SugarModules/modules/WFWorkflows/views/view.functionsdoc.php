<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */
require_once('include/TemplateHandler/TemplateHandler.php');
require_once('custom/include/Workflow/utils.php');

class WFWorkflowsViewFunctionsDoc extends SugarView
{
    public $type = 'functionsdoc';
    public $showTitle = true;
    public $view = 'FunctionsDoc';
    private $th;

    function preDisplay() {
        $this->th = new TemplateHandler();
        $this->th->ss =& $this->ss;
        $this->tpl = get_custom_file_if_exists('modules/WFWorkflows/tpls/FunctionsDoc.tpl');
    }

    function display()
    {
        $functionsDoc = array();
        foreach(wf_getFilterFunctions() as $class => $name) {
            if(!$class) {
                continue;
            }
            require_once 'custom/include/Workflow/functions/filters/'.$class.'.php';
            $functionsDoc['filters'][$class] = array(
                'name' => $name,
                'classname' => $class,
                'description' => $this->getClassDescription($class),
                'usages' => $this->getFilterUsages($class),
            );
        }
        foreach(wf_getValidateFunctions() as $class => $name) {
            if(!$class) {
                continue;
            }
            $functionsDoc['validators'][$class] = array(
                'name' => $name,
                'classname' => $class,
                'description' => $this->getClassDescription($class),
                'usages' => $this->getValidatorUsages($class),
            );
        }
        foreach(wf_getProcedures() as $class => $name) {
            if(!$class) {
                continue;
            }
            require_once 'custom/include/Workflow/functions/BaseProcedure.php';
            require_once 'custom/include/Workflow/functions/procedures/'.$class.'.php';
            $functionsDoc['procedures'][$class] = array(
                'name' => $name,
                'classname' => $class,
                'description' => $this->getClassDescription($class),
                'usages' => $this->getProcedureUsages($class),
            );
        }
        foreach(wf_getAssignedListFunctions() as $class => $name) {
            if(!$class) {
                continue;
            }
            $functionsDoc['userlists'][$class] = array(
                'name' => $name,
                'classname' => $class,
                'description' => $this->getClassDescription($class),
                'usages' => $this->getUserListUsages($class),
            );
        }

        $this->ss->assign('module', 'WFWorkflows');
        $this->ss->assign('functionsDoc', $functionsDoc);
        echo $this->getModuleTitle($this->showTitle);
        $this->ss->load_filter('output', 'trimwhitespace');
        echo $this->th->displayTemplate($this->module, $this->view, $this->tpl);
    }

    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;
        $params = array($this->_getModuleTitleListParam($browserTitle));
        $params[] = $mod_strings['LBL_FUNCTIONS_DOC'];
        return $params;
    }

    protected function getClassDescription($class)
    {
        $rc = new ReflectionClass($class);
        $comment = $rc->getDocComment();
        $lines = explode(PHP_EOL, $comment);
        foreach($lines as &$line) {
            $line = ltrim(ltrim($line), '/*');
            if(strpos(ltrim($line), '@') === 0) {
                $line = '';
            }
        }
        unset($line);
        $description = implode("\n", $lines);
        $description = preg_replace('/\n(\s*\n){2,}/', "\n\n", $description);
        $description = preg_replace('/^\n+/', '', $description);
        return $description;
    }

    protected function getFilterUsages($class)
    {
        global $db;
        $usages = array();
        $q = "SELECT w.name AS wf_name, e.id AS event_id, s1.name AS status1_name, s2.name AS status2_name
        FROM wf_events e
        INNER JOIN wf_workflows w ON e.workflow_id = w.id
        INNER JOIN wf_statuses s2 ON e.status2_id = s2.id
        LEFT JOIN wf_statuses s1 ON e.status1_id = s1.id
        WHERE e.filter_function = '$class'
             AND e.deleted = 0
             AND w.deleted = 0
             AND s2.deleted = 0
             AND (s1.deleted = 0 OR e.status1_id IS NULL OR e.status1_id = '')
        ORDER BY w.name, s1.name
        ";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $usages[] = $row;
        }
        return $usages;
    }

    protected function getValidatorUsages($class)
    {
        global $db;
        $usages = array();
        $q = "SELECT w.name AS wf_name, e.id AS event_id, s1.name AS status1_name, s2.name AS status2_name
        FROM wf_events e
        INNER JOIN wf_workflows w ON e.workflow_id = w.id
        INNER JOIN wf_statuses s2 ON e.status2_id = s2.id
        LEFT JOIN wf_statuses s1 ON e.status1_id = s1.id
        WHERE e.validate_function = '$class'
             AND e.deleted = 0
             AND w.deleted = 0
             AND s2.deleted = 0
             AND (s1.deleted = 0 OR e.status1_id IS NULL OR e.status1_id = '')
        ORDER BY w.name, s1.name
        ";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $usages[] = $row;
        }
        return $usages;
    }

    protected function getProcedureUsages($class)
    {
        global $db;
        $usages = array();
        $q = "SELECT w.name AS wf_name, e.id AS event_id, s1.name AS status1_name, s2.name AS status2_name, e.after_save
        FROM wf_events e
        INNER JOIN wf_workflows w ON e.workflow_id = w.id
        INNER JOIN wf_statuses s2 ON e.status2_id = s2.id
        LEFT JOIN wf_statuses s1 ON e.status1_id = s1.id
        WHERE e.after_save LIKE '%$class%'
             AND e.deleted = 0
             AND w.deleted = 0
             AND s2.deleted = 0
             AND (s1.deleted = 0 OR e.status1_id IS NULL OR e.status1_id = '')
        ORDER BY w.name, s1.name
        ";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            if(in_array($class, explode(',', $row['after_save']))) {
                $usages[] = $row;
            }
        }
        return $usages;
    }

    protected function getUserListUsages($class)
    {
        global $db;
        $usages = array();
        $status = BeanFactory::newBean('WFStatuses');
        $lbl = translate($status->field_defs['front_assigned_list_function']['vname'], 'WFStatuses');
        $q = "SELECT s.id, s.name FROM wf_statuses s WHERE s.front_assigned_list_function = '$class' AND s.deleted = 0 ORDER BY s.name";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $row['field'] = $lbl;
            $usages[] = $row;
        }
        $lbl = translate($status->field_defs['assigned_list_function']['vname'], 'WFStatuses');
        $q = "SELECT s.id, s.name FROM wf_statuses s WHERE s.assigned_list_function = '$class' AND s.deleted = 0 ORDER BY s.name";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $row['field'] = $lbl;
            $usages[] = $row;
        }
        $lbl = translate($status->field_defs['confirm_list_function']['vname'], 'WFStatuses');
        $q = "SELECT s.id, s.name FROM wf_statuses s WHERE s.confirm_list_function = '$class' AND s.deleted = 0 ORDER BY s.name";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $row['field'] = $lbl;
            $usages[] = $row;
        }
        $lbl = translate($status->field_defs['confirm_check_list_function']['vname'], 'WFStatuses');
        $q = "SELECT s.id, s.name FROM wf_statuses s WHERE s.confirm_check_list_function = '$class' AND s.deleted = 0 ORDER BY s.name";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $row['field'] = $lbl;
            $usages[] = $row;
        }
        return $usages;
    }
}
