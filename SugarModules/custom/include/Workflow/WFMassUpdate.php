<?php
require_once ('custom/include/Workflow/WFManager.php');
require_once ('custom/include/Workflow/utils.php');

class WFMassUpdate {

    protected $errors = array();
    protected $beans;
    protected $firstWorkflowBean;
    protected $statusField;
    protected $status1;
    protected $status2;
    protected $assigned2;
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getBeans() {
        return $this->beans;
    }
    
    public function setBeans($module, $beansIds) {
        $this->beans = array();
        $this->firstWorkflowBean = null;
        if(empty($beansIds)) {
            $this->errors[] = wf_translate('ERR_NO_RECORD');
            return;
        }
        
        $beanClass = BeanFactory::getBeanName($module);
        if (empty($beanClass) || !class_exists($beanClass)) {
            $this->errors[] = wf_translate('ERR_MODULE_NOT_FOUND');
            return false;
        }

        foreach($beansIds as $id) {
            $bean = new $beanClass();        // BeanFactory::getBean вернет бин из кэша
            $bean = $bean->retrieve($id);    // тогда fetched_row покажет не актуальную информацию
            if($bean) {                      // поэтому retrieve
                $this->beans[] = $bean;
            }
            else {
                $this->errors[] = wf_translate('ERR_SOME_RECORD_NOT_FOUND');
                return;
            }
        }
        
        $workflows = array();
        foreach($this->beans as $bean) {
            if(WFManager::isBeanInWorkflow($bean)) {
                $workflows[$bean->wf_id][] = $bean;
            }
            else {
                $this->errors[] = wf_translate('ERR_NO_WORKFLOW_FOR', array(
                    '#NAME#' => $bean->name,
                ));
            }
        }
        
        if(empty($workflows)) {
            return;
        }
        
        $firstWorkflow = reset($workflows);
        $firstWorkflowBean = reset($firstWorkflow);
        $this->firstWorkflowBean = $firstWorkflowBean;
        if(count($workflows) > 1) {    
            $lastWorkflow = end($workflows);
            $lastWorkflowBean = reset($lastWorkflow);
            $this->errors[] = wf_translate('ERR_NOT_SAME_WORKFLOW', array(
                '#NAME1#' => $firstWorkflowBean->name,
                '#NAME2#' => $lastWorkflowBean->name,
            ));
            return;
        }
        
        $statuses1 = array();
        $statusField = WFManager::getBeanStatusField($firstWorkflowBean);
        if(!$statusField) {
            $this->errors[] = wf_translate('ERR_STATUS_FIELD_NOT_FOUND');
            return;
        }
        $this->statusField = $statusField;
        
        foreach($this->beans as $bean) {
            $this->status1 = $bean->$statusField;
            $statuses1[$this->status1][] = $bean;
        }
        
        if(count($statuses1) > 1) {
            $firstStatus = reset($statuses1);
            $firstStatusBean = reset($firstStatus);
            $lastStatus = end($statuses1);
            $lastStatusBean = reset($lastStatus);
            $this->errors[] = wf_translate('ERR_NOT_SAME_STATUS', array(
                '#NAME1#' => $firstStatusBean->name,
                '#NAME2#' => $lastStatusBean->name,
            ));
            return;
        }
    }
    
    public function setNextStatus($status2, $assigned2) {
        if(!$status2) {
            $this->errors[] = wf_translate('ERR_STATUS_REQUIRED');
            return;
        }
        if($this->status1 == $status2) {
            $this->errors[] = wf_translate('ERR_STATUS_NOT_CHANGING');
            return;
        }
        if(!$assigned2) {
            $this->errors[] = wf_translate('ERR_ASSIGNED_REQUIRED');
            return;
        }
        $this->status2 = $status2;
        $this->assigned2 = $assigned2;
        
        foreach($this->beans as $bean) {
            if(!WFManager::isEventAllowed($bean, $this->status1, $this->status2)) {
                $this->errors[] = wf_translate('ERR_CONFIRM_INVALID_FOR', array(
                    '#NAME#' => $bean->name,
                ));
            }
            if(!empty($bean->fetched_row['id'])) {
                if(!WFManager::canChangeStatus($bean, $this->status1)) {
                    $this->errors[] = wf_translate('ERR_CONFIRM_DENIED_FOR', array(
                        '#NAME#' => $bean->name,
                    ));
                }
                if(!WFManager::isInFrontAssignedUsers($this->assigned2, $bean, $this->status2)) {
                    $this->errors[] = wf_translate('ERR_ASSIGNED_INVALID_FOR', array(
                        '#NAME#' => $bean->name,
                    ));
                }
            }
        }
    }
    
    public function saveBeans($attributes) {
        $res = true;
        foreach($this->beans as $bean) {
            $bean->{$this->statusField} = $this->status2;
            $bean->assigned_user_id = $this->assigned2;
            foreach($attributes as $name => $value) {
                $bean->$name = $value;
            }
            $res = $bean->save(true) && $res;
        }
        return $res;
    }
    
    public function getWorkflowBean() {
        return $this->firstWorkflowBean;
    }
    
    public function massConfirmDisplay($bean, $event) {
        $action = $_REQUEST['action'];
        $module = $_REQUEST['module'];
        
        $action = strtolower($action);
        if(!$module || ($action != "list" && $action != "index" && $action != "listview")
             || (isset($_REQUEST['search_form_only']) && $_REQUEST['search_form_only'])) {
            return;
        }
        
        require_once 'include/Sugar_Smarty.php';
        $ss = new Sugar_Smarty();
        $workflow = array(
            'include_script' => WFManager::getVersionedScript(),
            'currentStatus' => '',
            'confirmData' => array(
                'formName' => 'confirmForm',
                'newStatuses' => array(),
                'assignedUsersString' => '[]',
                'confirmFunc' => 'lab321.wf.massConfirmSave',
            ),
        );
        $ss->assign('workflow', $workflow);
        
        echo $ss->fetch('custom/include/Workflow/tpls/AfterListFrame.tpl');
    }
}
