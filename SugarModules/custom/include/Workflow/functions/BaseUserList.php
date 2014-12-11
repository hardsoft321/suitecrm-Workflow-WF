<?php
/**
 * Базовый класс с функцией, определяющей список пользователей.
 */
abstract class BaseUserList {
    public $status_data;
    
    public abstract function getList($bean);

    public function getName() {
        return get_class($this);
    }
    
    protected function getUsersBySql($sql) {
        global $db;
        $qr = $db->query($sql);
        $users = array();
        while($row = $db->fetchByAssoc($qr)) {
            $user = BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            $users[$user->id] = $user;
        }
        return $users;
    }
}
