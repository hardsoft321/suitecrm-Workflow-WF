<?php
class WFStatusAssigned {

    public static function addAssignedUser($status_id, $record_id, $module, $user_id) {
        global $db;
        $current_date = $db->now();
        $guidSql = $db->getGuidSQL();
        $insert_query = "INSERT INTO wf_status_assigned (id, status_id, record_id, module, user_id, date_modified) VALUES ".
                                          "({$guidSql}, '$status_id', '$record_id', '$module', '$user_id', $current_date)";
        $db->query($insert_query);
    }
    
    public static function hasAssignedUser($status_id, $record_id, $module, $user_id = null) {
        global $db;
        $query= "SELECT 1 FROM wf_status_assigned WHERE status_id = '$status_id' AND record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        return (bool)($db->fetchOne($query));
    }
    
    /**
     * Возвращает пользователей, ответственных на данном статусе
     */
    public static function getAssignedUsers($status_id, $record_id, $module) {
        global $db;
        $query= "SELECT DISTINCT users.* FROM wf_status_assigned a, users WHERE a.status_id = '$status_id' AND a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0 
            AND a.user_id = users.id AND users.deleted = 0";
        $dbRes = $db->query($query);
        $users = array();
        while($row = $db->fetchByAssoc($dbRes)) {
            $user = BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            $users[$user->id] = $user;
        }
        return $users;
    }
    
    /**
     * Возвращает пользователей, ответственных на данном статусе и на родительском
     */
    public static function getAssignedUsersEx($status_id, $record_id, $module) {
        global $db;
        $query = "SELECT DISTINCT users.* FROM wf_status_assigned a, users
        WHERE (a.status_id = '$status_id' OR a.status_id IN (
                SELECT parent_status_id FROM wf_statuses WHERE id = '$status_id' AND deleted = 0
            ))
            AND a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0 
            AND a.user_id = users.id AND users.deleted = 0
        ";

        $dbRes = $db->query($query);
        $users = array();
        while($row = $db->fetchByAssoc($dbRes)) {
            $user = BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            $users[$user->id] = $user;
        }
        return $users;
    }
        
    public static function deleteAssignedUser($status_id, $record_id, $module, $user_id = null) {
        global $db;
        $query = "UPDATE wf_status_assigned SET deleted = 1 WHERE status_id = '$status_id' AND record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        $db->query($query);
    }
}
