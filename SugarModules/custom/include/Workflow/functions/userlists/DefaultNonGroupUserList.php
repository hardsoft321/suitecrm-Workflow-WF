<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.7.15
 */
require_once __DIR__.'/DefaultUserList.php';

/**
 * Все кроме групповых в роли и в группе
 * К функции "Все в роли и группе" добавляется условие так, что возвращаются
 * только не групповые пользователи.
 */
class DefaultNonGroupUserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->additionalWhere = 'users.is_group = 0';
        return parent::getList($bean);
    }
}
?>
