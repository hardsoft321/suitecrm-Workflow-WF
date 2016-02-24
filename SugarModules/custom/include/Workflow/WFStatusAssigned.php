<?php
class WFStatusAssigned {

    public static function setAssignedUser($statusBean, $record_id, $module, $user_id) {
        self::deleteAssignedUser($statusBean, $record_id, $module);
        self::addAssignedUser($statusBean, $record_id, $module, $user_id);
    }
    
    public static function addAssignedUser($statusBean, $record_id, $module, $user_id) {
        global $db;
        list($role_id, $status_id) = self::getRoleAndStatusId($statusBean);
        $current_date = $db->now();
        $guidSql = $db->getGuidSQL();
        $insert_query = "INSERT INTO wf_status_assigned (id, role_id, status_id, record_id, module, user_id, date_modified) VALUES ".
                                          "({$guidSql}, '$role_id', '$status_id', '$record_id', '$module', '$user_id', $current_date)";
        $db->query($insert_query);
    }
    
    public static function hasAssignedUser($statusBean, $record_id, $module, $user_id = null) {
        global $db;
        list($role_id, $status_id) = self::getRoleAndStatusId($statusBean);
        $query= "SELECT 1 FROM wf_status_assigned WHERE record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        if(!empty($role_id)) {
            $query .= " AND role_id = '$role_id'";
        }
        else {
            $query .= " AND status_id = '$status_id'";
        }
        return (bool)($db->fetchOne($query));
    }
    
    public static function deleteAssignedUser($statusBean, $record_id, $module, $user_id = null) {
        global $db;
        list($role_id, $status_id) = self::getRoleAndStatusId($statusBean);
        $query = "UPDATE wf_status_assigned SET deleted = 1 WHERE record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        if(!empty($role_id)) {
            $query .= " AND role_id = '$role_id'";
        }
        else {
            $query .= " AND status_id = '$status_id'";
        }
        $db->query($query);
    }
    
    /**
     * Возвращает пользователей, ответственных на данном статусе
     */
    public static function getAssignedUsers($statusBean, $record_id, $module) {
        global $db;
        list($role_id, $status_id) = self::getRoleAndStatusId($statusBean);
        $query = "SELECT DISTINCT users.* FROM wf_status_assigned a, users WHERE a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0
                AND a.user_id = users.id AND users.deleted = 0 AND users.status != 'Inactive'";
        if(!empty($role_id)) {
            $query .= " AND a.role_id = '$role_id'";
        }
        else {
            $query .= " AND a.status_id = '$status_id'";
        }
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
        $query = "SELECT DISTINCT a.role_id, a.status_id, acl_roles.name AS role_name,
            wf_statuses.name AS status_name, users.id AS user_id, users.last_name, users.first_name
        FROM wf_status_assigned a
        INNER JOIN users ON a.user_id = users.id AND users.deleted = 0 AND users.status != 'Inactive'
        LEFT JOIN acl_roles ON acl_roles.id = a.role_id AND acl_roles.deleted = 0
        LEFT JOIN wf_statuses ON wf_statuses.id = a.status_id AND wf_statuses.deleted = 0
        WHERE a.record_id = '$record_id' AND a.module = '$module' AND a.deleted = 0";
        $dbRes = $db->query($query);
        $res = array();
        while($row = $db->fetchByAssoc($dbRes)) {
            if(empty($row['role_id'])) {
                $row['role_name'] = $row['status_name'];
            }
            $res[] = $row;
        }
        return $res;
    }

    protected static function getRoleAndStatusId($statusBean) {
        if(is_string($statusBean)) { //в старой версии передавался только role_id
            $role_id = $statusBean;
            $status_id = '';
        }
        else {
            $role_id = $statusBean->role_id;
            $status_id = $statusBean->id;
        }
        return array($role_id, $status_id);
    }
}
