<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

require_once('include/DetailView/DetailView2.php');

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class ViewDetail extends SugarView
{
    /**
     * @see SugarView::$type
     */
    public $type = 'detail';
	
    /**
     * @var DetailView2 object 
     */
    public $dv;
	
    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function ViewDetail()
    {
        parent::SugarView();
    }
	
    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        /* BEGIN - SECURITY GROUPS */ 
        /** moved to end of block
 	    $metadataFile = $this->getMetaDataFile();
        */
        $metadataFile = null;
        $foundViewDefs = false;
        if(empty($_SESSION['groupLayout'])) {
            //get primary group id of current user and check to see if a layout exists for that group
            require_once('modules/SecurityGroups/SecurityGroup.php');
            $primary_group_id = SecurityGroup::getPrimaryGroupID();
            if(!empty($primary_group_id) && file_exists('custom/modules/' . $this->module . '/metadata/'.$primary_group_id.'/detailviewdefs.php')){
                $_SESSION['groupLayout'] = $primary_group_id;
                $metadataFile = 'custom/modules/' . $this->module . '/metadata/'.$primary_group_id.'/detailviewdefs.php';
            }       
        } else {
            if(file_exists('custom/modules/' . $this->module . '/metadata/'.$_SESSION['groupLayout'].'/detailviewdefs.php')){
                $metadataFile = 'custom/modules/' . $this->module . '/metadata/'.$_SESSION['groupLayout'].'/detailviewdefs.php';
            }       
        }       
        if(isset($metadataFile)){
            $foundViewDefs = true;
        }
        else {      
        $metadataFile = $this->getMetaDataFile();
        }
        /* END - SECURITY GROUPS */ 
 	    $this->dv = new DetailView2();
 	    $this->dv->ss =&  $this->ss;
 	    $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
		
/*************************
* NLV START
**************************/
        require_once ('custom/include/Workflow/WFManager.php');
        //Если модуль в маршруте
        if(WFManager::isBeanInWorkflow($this->bean)) {
            global $current_user;
            $statuses = WFManager::getNextStatuses($this->bean);

            if (count($statuses) > 0) {
              $this->dv->ss->assign("workflow", array (
                  'newStatuses' => $statuses, 
                  'executers' => array(),
                  'errors' => array()
              ));
                    // TODO это вообще должно определятся и так
                    //$this->dv->ss->assign("module", $this->module);
              $this->dv->ss->assign("return_module", $this->module);
              $this->dv->ss->assign("return_action", $this->action);
              $this->dv->ss->assign("return_record", $this->bean->id);
            }
            
            /*$statusAudit = WFManager::getStatusAuditForBean($this->bean);
            if($statusAudit) {
                $timeDate = new TimeDate();
                foreach($statusAudit as &$row) {
                    $row['date'] = $timeDate->to_display_date_time($row['date_created'], true, true, $current_user);
                }
                unset($row);
                $this->dv->ss->assign("wf_statusAudit", $statusAudit);
            }*/
        }
/*************************
* NLV END
**************************/
    } 	
 	
    /**
     * @see SugarView::display()
     */
    public function display()
    {
        if(empty($this->bean->id)){
            sugar_die($GLOBALS['app_strings']['ERROR_NO_RECORD']);
        }				
        $this->dv->process();
        echo $this->dv->display();
    }
}
