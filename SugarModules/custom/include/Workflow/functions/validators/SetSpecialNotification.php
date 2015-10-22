<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * Перед переходом устанавливается признак special_notification.
 * То есть, если будет отправлено письмо, то будет выбран специальныхй шаблон.
 */
class SetSpecialNotification extends BaseValidator
{
    public function validate($bean)
    {
        $bean->special_notification = true;
        return array();
    }
}
