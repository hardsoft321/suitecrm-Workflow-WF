<?php
class WFStatusAssigned {

    public static function setAssignedUser($role_id, $record_id, $module, $user_id) {
        self::deleteAssignedUser($role_id, $record_id, $module);
        self::addAssignedUser($role_id, $record_id, $module, $user_id);
    }
    
    public static function addAssignedUser($role_id, $record_id, $module, $user_id) {
        global $db;
        $current_date = $db->now();
        $guidSql = $db->getGuidSQL();
        $insert_query = "INSERT INTO wf_status_assigned (id, role_id, record_id, module, user_id, date_modified) VALUES ".
                                          "({$guidSql}, '$role_id', '$record_id', '$module', '$user_id', $current_date)";
        $db->query($insert_query);
    }
    
    public static function hasAssignedUser($role_id, $record_id, $module, $user_id = null) {
        global $db;
        $query= "SELECT 1 FROM wf_status_assigned WHERE role_id = '$role_id' AND record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        return (bool)($db->fetchOne($query));
    }
    
    public static function deleteAssignedUser($role_id, $record_id, $module, $user_id = null) {
        global $db;
        $query = "UPDATE wf_status_assigned SET deleted = 1 WHERE role_id = '$role_id' AND record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        $db->query($query);
    }
    
    /**
     * Возвращает пользователей, ответственных на данном статусе
     */
    public static function getAssignedUsers($role_id, $record_id, $module) {
        global $db;
        $query= "SELECT DISTINCT users.* FROM wf_status_assigned a, users WHERE a.role_id = '$role_id' AND a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0 
            AND a.user_id = users.id AND users.deleted = 0 AND users.status != 'Inactive'";
        $dbRes = $db->query($query);
        $users = array();
        while($row = $db->fetchByAssoc($dbRes)) {
            $user = BeanFactory::newBean('Users');
            $user->populateFromRow($row);
            $users[$user->id] = $user;
        }
        return $users;
    }
    
    public static function getAllAssignedUsers($record_id, $module) {
        global $db;
        $query = "SELECT DISTINCT a.role_id, acl_roles.name AS role_name, users.id AS user_id, users.last_name,  users.first_name
        FROM wf_status_assigned a, users, acl_roles 
        WHERE a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0 
            AND a.user_id = users.id AND users.deleted = 0 AND users.status != 'Inactive'
            AND acl_roles.id = a.role_id AND acl_roles.deleted = 0";
        $dbRes = $db->query($query);
        $res = array();
        while($row = $db->fetchByAssoc($dbRes)) {
            $res[] = $row;
        }
        return $res;
    }
}
