<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 */
require_once 'custom/include/Workflow/functions/BaseProcedure.php';

/**
 * Если указан дополнительный ответственный, ему отправляется письмо.
 * Чтобы можно было выбрать дополнительного ответственного при переходе,
 * нужно для модуля подключить хук WF_hooks::displayNotificationFields.
 * @since version 0.7.9.7
 */
class SendNotificationCopy extends BaseProcedure
{
    public function doWork($bean) {
        require_once 'custom/include/SugarBeanMailer.php';
        if(!empty($_POST['assigned_user_copy'])) {
            $usersIds = array();
            if(is_array($_POST['assigned_user_copy'])) {
                foreach($_POST['assigned_user_copy'] as $fields) {
                    $usersIds[] = $fields['id'];
                }
            }
            else {
                $usersIds[] = $_POST['assigned_user_copy'];
            }
            $users = array();
            foreach($usersIds as $id) {
                if($id) {
                    $user = BeanFactory::getBean('Users', $id);
                    if($user) {
                        $users[] = $user;
                    }
                    else {
                        $GLOBALS['log']->error('SendNotificationCopy: user not found '.$id);
                    }
                }
            }
            if(!empty($users)) {
                $assigned = reset($bean->get_notification_recipients());
                $mailer = new SugarBeanMailer($bean);
                $mailer->set_notification_recipients($users);
                $mailer->setTemplate('Default', array(
                    'ASSIGNED_USER' => $assigned->full_name,
                ));
                $mailer->sendNotifications();
            }
            unset($_POST['assigned_user_copy']); //чтобы два раза не отправить, если SendNotificationCopy настроена на переходе в after_save
        }
    }
}
