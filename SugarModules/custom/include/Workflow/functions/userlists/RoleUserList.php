<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.13.0
 *
 * Все пользователи в роли
 *
 * Возвращает список пользователей, находящихся в роли,
 * настроенной в поле "Роль" данного статуса.
 * Группа не учитывается.
 * Пользователи со статусом "Не активен" игнорируются.
 */
class RoleUserList extends BaseUserList
{
    protected $statusRoleField = 'role_id';
    protected $additionalWhere = '';

    public function getList($bean)
    {
        $q = "SELECT DISTINCT users.*
              FROM users, acl_roles_users
              WHERE users.id = acl_roles_users.user_id
                AND users.is_group = 1 AND users.status != 'Inactive'
                AND users.deleted = 0 AND acl_roles_users.deleted = 0
                AND acl_roles_users.role_id = '{$this->status_data[$this->statusRoleField]}'";
        if($this->additionalWhere) {
            $q .= " AND ".$this->additionalWhere;
        }
        $q .= " ORDER BY users.last_name";
        return parent::getUsersBySql($q);
    }
}
