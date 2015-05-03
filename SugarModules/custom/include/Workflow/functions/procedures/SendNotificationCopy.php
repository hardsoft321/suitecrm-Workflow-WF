<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 */


/**
 * Если указан дополнительный ответственный, ему отправляется письмо.
 * @since version 0.7.9.7
 */
class SendNotificationCopy extends BaseProcedure
{
    public function doWork($bean) {
        require_once 'custom/include/SugarBeanMailer.php';
        if(!empty($_POST['assigned_user_copy'])) {
            $user = BeanFactory::getBean('Users', $_POST['assigned_user_copy']);
            if($user) {
                $assigned = reset($bean->get_notification_recipients());
                $mailer = new SugarBeanMailer($bean);
                $mailer->set_notification_recipients(array($user));
                $mailer->setTemplate('Default', array(
                    'ASSIGNED_USER' => $assigned->full_name,
                ));
                $mailer->sendNotifications();
            }
            else {
                $GLOBALS['log']->error('SendNotificationCopy: не найден пользователь '.$_POST['assigned_user_copy']);
            }
        }
    }
}
