<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */

require_once 'include/MVC/View/views/view.detail.php';

class WFStatusesViewDetail extends ViewDetail
{
    public function display()
    {
        parent::display();

        if ($this->bean->edit_role_type == 'nobody') {
            $workflow = $GLOBALS['db']->fetchOne(
                "SELECT id, status_field FROM wf_workflows WHERE wf_module = '{$this->bean->wf_module}'");
            if (!empty($workflow)) {
                $statusField = $workflow['status_field'];
                $exampleBean = BeanFactory::newBean($this->bean->wf_module);
                $exampleBean->wf_id = $workflow['id'];
                $exampleBean->$statusField = $this->bean->uniq_name;
                $exampleBean->assigned_user_id = $GLOBALS['current_user']->id;
                $is_admin = isset($GLOBALS['current_user']->is_admin) ? $GLOBALS['current_user']->is_admin : null;
                $aclaccess = isset($_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['access'])
                    ? $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['access']
                    : null;
                $acledit = isset($_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['edit']['aclaccess'])
                    ? $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['edit']['aclaccess']
                    : null;
                $GLOBALS['current_user']->is_admin = '0';
                $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['access'] = array(
                    'aclaccess' => ACL_ALLOW_ENABLED
                );
                $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['edit']['aclaccess'] = ACL_ALLOW_OWNER;
                if ($exampleBean->ACLAccess('edit', true, true)) {
                    echo '
<div style="color: red">
You have set "' . translate('LBL_EDIT_ROLE_TYPE')
. '" to the value "' . $GLOBALS['app_list_strings']['edit_role_types']['nobody'] . '".<br />'
. "This will not work until you update ACLAccess function in <strong>{$GLOBALS['beanFiles'][$exampleBean->object_name]}</strong>"
. ' or in <strong>data/SugarBean.php</strong> with following code.
<pre>';
echo <<<'CODE'
    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        global $current_user;
        if ($current_user->isAdmin()) {
            return true;
        }
        $view = strtolower($view);
        switch ($view) {
            case 'edit':
            case 'save':
            case 'popupeditview':
            case 'editview':
                $view = "edit";
                break;
            case 'delete':
                $view = "delete";
                break;
            default:
                return parent::ACLAccess($view, $is_owner, $in_group);
        }
        if ($view === 'edit' || $view === 'delete') {
            if (file_exists('custom/include/Workflow/WFManager.php')) {
                require_once 'custom/include/Workflow/WFManager.php';
                if (!WFManager::checkAccess($this, $view)) {
                    return false;
                }
            }
        }
        return parent::ACLAccess($view, $is_owner, $in_group);
    }
CODE;
echo "</pre>
Warning: you must be a php-developer.<br />
This is an example for file {$GLOBALS['beanFiles'][$exampleBean->object_name]} provided there is no <strong>ACLAccess</strong> function yet. <br/>";

if (!$exampleBean->bean_implements('ACL')) {
    echo "You should know that <strong>{$exampleBean->object_name}</strong> does not implement <strong>ACL</strong>.<br />";
}
echo "</div>";
                }
                $GLOBALS['current_user']->is_admin = $is_admin;
                $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['edit']['aclaccess'] = $acledit;
                $_SESSION['ACL'][$GLOBALS['current_user']->id][$this->bean->wf_module]['module']['access'] = $aclaccess;
            }
        }
    }
}
