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
        require_once 'custom/include/NotificationCopy/NotificationCopy.php';
        $nc = new NotificationCopy();
        $nc->send($bean);
    }
}
