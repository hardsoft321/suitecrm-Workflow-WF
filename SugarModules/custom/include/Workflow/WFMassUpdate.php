<?php
require_once ('custom/include/Workflow/WFManager.php');

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
            $this->errors[] = "Ни одна запись не выбрана";
            return;
        }
        
        foreach($beansIds as $id) {
            $bean = BeanFactory::getBean($module, $id);
            if($bean) { 
                $this->beans[] = $bean;
            }
            else {
                $this->errors[] = "Не все записи найдены";
                return;
            }
        }
        
        $workflows = array();
        foreach($this->beans as $bean) {
            if(WFManager::isBeanInWorkflow($bean)) {
                $workflows[$bean->wf_id][] = $bean;
            }
            else {
                $this->errors[] = "Нет маршрута для записи '{$bean->name}'";
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
            $this->errors[] = "Невозможно перевести на один статус записи, находящиеся в разных маршрутах (записи '$firstWorkflowBean->name' и '$lastWorkflowBean->name')";
            return;
        }
        
        $statuses1 = array();
        $statusField = WFManager::getBeanStatusField($firstWorkflowBean);
        if(!$statusField) {
            $this->errors[] = "Не удается определить статус";
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
            $this->errors[] = "Невозможно перевести на один статус записи, находящиеся на разных статусах (записи '$firstStatusBean->name' и '$lastStatusBean->name')";
            return;
        }
    }
    
    public function setNextStatus($status2, $assigned2) {
        if(!$status2) {
            $this->errors[] = "Необходимо выбрать статус";
            return;
        }
        if($this->status1 == $status2) {
            $this->errors[] = "Необходимо выбрать следующий статус";
            return;
        }
        if(!$assigned2) {
            $this->errors[] = "Необходимо выбрать ответственного";
            return;
        }
        $this->status2 = $status2;
        $this->assigned2 = $assigned2;
        
        foreach($this->beans as $bean) {
            if(!WFManager::isEventAllowed($bean, $this->status1, $this->status2)) {
                $this->errors[] = "Невозможно сменить статус для записи '$bean->name'";
            }
            if(!empty($bean->fetched_row['id'])) {
                if(!WFManager::canChangeStatus($bean, $this->status1)) {
                    $this->errors[] = "Вы не можете сменить статус для записи '$bean->name'";
                }
                if(!WFManager::isInFrontAssignedUsers($this->assigned2, $bean, $this->status2)) {
                    $this->errors[] = "Ответственный задан не верно для записи '$bean->name'";
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
            'confirmData' => array(
                'newStatuses' => array(),
                'assignedUsersString' => '[]',
                'currentStatus' => '',
            ),
        );
        $ss->assign('workflow', $workflow);
        
        echo $ss->fetch('custom/include/Workflow/tpls/AfterListFrame.tpl');
    }
}
