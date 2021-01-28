<?php

class Model_Users extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getModuleData($user_id) {
        $modules = $this->getTableRow(T_CONSULT_MODULES, ['user_id' => $user_id]);
        if ($modules != null) {
            $module[0] = $modules->module1;
            $module[1] = $modules->module1;
            $module[2] = $modules->module1;
            return $module;
        }
        return null;
    }

    public function getConsultants() {
        return $this->db->select('u.user_id,u.first_name,u.last_name,u.username,c.address,c.shift,dep.type as department,
        c.comment,c.demeanor,cm.module1,cm.module2,cm.module3,cm.rating1,cm.rating2,cm.rating3,c.team_lead_uid')
                        ->from(T_USERS . ' AS u')
                        ->join(T_CONSULTANTS . ' AS c', 'c.user_id=u.user_id', 'LEFT')
                        ->join(T_DEPTS . ' AS dep', 'dep.dept_id=c.department', 'LEFT')
                        ->join(T_CONSULT_MODULES . ' AS cm', 'cm.user_id=u.user_id', 'LEFT')
                        ->where('u.type', 'consultant')
                        ->get()->result();
    }

    public function getSecondModules($user_id) {
        $modules = $this->getTableRow(T_CONSULT_MODULES, ['user_id' => $user_id]);
        if (!empty($modules)) {
            return $this->db->select('module_id,name')
                            ->from(T_MODULES)
                            ->where('name!=', $modules->module1)->where('name!=', $modules->module3)->get()->result();
        } else {
            return null;
        }
    }

    public function getThirdModules($user_id) {
        $modules = $this->getTableRow(T_CONSULT_MODULES, ['user_id' => $user_id]);
        if (!empty($modules)) {
            return $this->db->select('module_id,name')
                            ->from(T_MODULES)
                            ->where('name!=', $modules->module1)->where('name!=', $modules->module2)->get()->result();
        } else {
            return null;
        }
    }

    public function getFacilityManagers() {
        return $this->db->select('u.first_name,u.last_name,u.username,u.phone,GROUP_CONCAT(l.location_id SEPARATOR", ") AS locations,u.user_id,f.fac_id as fac_id')
                        ->from(T_USERS . ' AS u')
                        ->join(T_FACILITY_LOCATION_MANAGERS . ' AS pj', 'pj.user_id=u.user_id', 'LEFT')
                        ->join(T_FACILITIES.' AS f','f.fac_id=pj.facility_id','LEFT')
                        ->join(T_FACILITY_LOCATION_MANAGERS_LOCATIONS . ' AS pml', 'pml.user_id=u.user_id', 'LEFT')
                        ->join(T_LOCATIONS . ' AS l', 'l.location_id=pml.location_id', 'LEFT')
                        ->where('u.type', 'facility_location_manager')
                        ->group_by('u.user_id')
                        ->order_by('u.user_id')
                        ->get()->result();
    }

    public function getLocations() {
        return $this->db->select('l.*,f.name as fac_name,d.type as dept_type')
                        ->from(T_LOCATIONS . ' AS l')
                        ->join(T_FACILITIES . ' AS f', 'f.fac_id=l.facility_id')
                        ->join(T_DEPTS . ' AS d', 'd.dept_id=l.type')
                        ->get()->result();
    }
    
    public function getSuperUsersForShift($num_of_su,$shift,$location_id){
        return $this->db->select('super_users.*,users.first_name,users.user_id')
                ->from(T_SUPER_USERS)
                ->join(T_USERS,'users.user_id=super_users.user_id')
                ->where('shift',$shift)
                ->where('location_id',$location_id)
                ->limit($num_of_su)
                ->order_by('su_id')
                ->get()->result();
    }
    
    public function getConsultantForShift($num_of_const,$shift,$module1_name,$module2_name,$pref_skill1,$pref_skill2,$demeanor,$location_id){
        $condition = [
            'c.shift'=>$shift,
            'c.demeanor>='=>$demeanor
        ];
        return $this->db->select('c.*,u.first_name,u.user_id')
                ->from(T_CONSULTANTS.' AS c')
                ->join(T_CONSULT_MODULES.' AS cm', 'c.user_id=cm.user_id','LEFT')
                ->join(T_USERS.' AS u','u.user_id=c.user_id','LEFT')
                ->join(T_DEPTS.' AS d','d.dept_id=c.department','LEFT')
                ->join(T_LOCATIONS.' AS l','l.type=d.dept_id','LEFT')
                ->where($condition)
                ->where('cm.module1',$module1_name)
                ->where('cm.module2',$module2_name)
                ->or_where('cm.module3', $module2_name)
                ->where('cm.rating1>=',$pref_skill1)
                ->where('cm.rating2>=',$pref_skill2)
                ->where('l.location_id',$location_id)
                ->where('shift',$shift)
                ->limit($num_of_const)
                ->order_by('c.consultant_id')
                ->get()->result();
    }
    
    public function checkConsultantAvailable($shift,$module1,$module2,$pref_skill1,$pref_skill2,$demeanor,$location_id){
        $condition = [
            'c.shift'=>$shift,
            'c.demeanor>='=>$demeanor
        ];
        $consultants = $this->db->select('*')
                ->from(T_CONSULTANTS.' AS c')
                ->join(T_CONSULT_MODULES.' AS cm', 'c.user_id=cm.user_id')
                ->join(T_DEPTS.' AS d','d.dept_id=c.department','LEFT')
                ->join(T_LOCATIONS.' AS l','l.type=d.dept_id','LEFT')
                ->where($condition)
                ->where('cm.module1',$module1)
                ->where('cm.module2',$module2)
                ->or_where('cm.module3',$module2)
                ->where('cm.rating1>=',$pref_skill1)
                ->where('cm.rating2>=',$pref_skill2)
                ->where('l.location_id',$location_id)
                ->get()->result();
        
        return count($consultants);
    }

}
