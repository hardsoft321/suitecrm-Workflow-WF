<?php
/**
 * Класс с функцией, определяющей список ответственных, 
 * появляющихся в списке выбора при переходе на статус.
 */
abstract class BaseUserList {
    public $status2_data;
    
    public abstract function getList($bean);

    public function getName($bean) {
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
