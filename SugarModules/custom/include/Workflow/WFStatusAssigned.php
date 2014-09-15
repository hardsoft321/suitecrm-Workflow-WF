<?php
class WFStatusAssigned {

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
    
    public static function hasAssignedUserByStatus($status, $record_id, $module, $user_id = null) {
        global $db;
        $query = "SELECT 1 FROM wf_status_assigned sa, wf_statuses s1
        WHERE
            sa.role_id = s1.role_id
            AND sa.record_id = '$record_id' AND sa.module = '$module' AND sa.deleted = 0
            AND s1.uniq_name='{$status}' AND s1.wf_module = '{$module}' AND s1.deleted = 0
        ";
        if($user_id !== null)
            $query .= " AND sa.user_id = '$user_id' ";
        return (bool)($db->fetchOne($query));
    }
    
    public static function deleteAssignedUser($role_id, $record_id, $module, $user_id = null) {
        global $db;
        $query = "UPDATE wf_status_assigned SET deleted = 1 WHERE role_id = '$role_id' AND record_id = '$record_id' AND module = '$module' AND deleted = 0";
        if($user_id !== null)
            $query .= " AND user_id = '$user_id' ";
        $db->query($query);
    }
}
