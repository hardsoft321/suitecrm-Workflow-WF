<?php
class AllUserList extends BaseUserList {
    
    public function getList($bean) {
        global $db;
        $q = "SELECT * FROM users WHERE deleted = 0 ORDER BY last_name";
        $qr = $db->query($q);
        $users = array();
        while($row = $db->fetchByAssoc($qr)) {
            $user = BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            $users[$user->id] = $user;
        }
        return $users;
    }
    
    public function getName() {
        return 'Все пользователи';
    }
}
?>
