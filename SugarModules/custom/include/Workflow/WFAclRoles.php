<?php
class WFAclRoles {

    public static function userHasRole($role_id, $user_id = null) {
        global $current_user, $db;
        if($user_id === null)
            $user_id = $current_user->id;
        $q = "SELECT 1 FROM acl_roles_users WHERE role_id = '$role_id' AND user_id = '$user_id' AND deleted = 0";
        return (bool)($db->fetchOne($q));
    }
    
    public static function getUsersWithRole($role_id) {
        global $db;
        $q = "SELECT 1 FROM acl_roles_users WHERE role_id = '$role_id' AND user_id = '$user_id' AND deleted = 0";
        return (bool)($db->fetchOne($q));
    }
}
