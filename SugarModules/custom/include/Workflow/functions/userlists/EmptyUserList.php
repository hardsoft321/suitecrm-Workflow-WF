<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.7.9.6
 *
 * Пустой список
 */
class EmptyUserList extends BaseUserList {
    public function getList($bean) {
        return array();
    }
}
