<?php

if (!defined('BASEPATH'))
    exit('Not allowed');

class Features extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Users', 'mu');

        if (!$this->isLoggedIn && $this->userType != 'app_admin') {
            $this->session->set_flashdata('error-msg', 'You do not have permission to view this page.');
            redirect('Auth/login');
        }
    }

    public function addModule()
    {
        if ($this->input->post('submit')) {
            $module_name = $this->input->post('module_name', true);


            if (!empty($module_name)) {
                $this->mu->insertIntoTable(T_MODULES, [
                    'name' => $module_name,
                    'created_at' => CURR_DATETIME
                ]);

                $this->session->set_flashdata('success-msg', 'Module Saved');
                redirect('admin/Features/addModule');
            } else {
                $this->session->set_flashdata('error-msg', 'Please fill both fields');
            }
        }
        $this->processViewData('admin/add-module', 'Module List');
    }

    public function moduleList()
    {
        $this->data['modules'] = $this->mu->getTableData(T_MODULES);
        $this->data['totalRow'] = count($this->data['modules']);
        $this->processViewData('admin/list-module', 'Module List');
    }

    public function addDept()
    {
        if ($this->input->post('submit')) {
            $dept_name = $this->input->post('dept_name', true);
            if (!empty($dept_name)) {
                $this->mu->insertIntoTable(T_DEPTS, [
                    'type' => $dept_name,
                    'created_at' => CURR_DATETIME
                ]);

                $this->session->set_flashdata('success-msg', 'Department Saved');
                redirect('admin/Features/deptList');
            } else {
                $this->session->set_flashdata('error-msg', 'Please fill required field');
            }
        }
    }

    public function deptList()
    {
        $this->data['depts'] = $this->mu->getTableData(T_DEPTS);
        $this->data['totalRow'] = count($this->data['depts']);
        $this->processViewData('admin/list-dept', 'Department List');
    }

    public function consultants()
    {
        $this->data['consultants'] = $this->mu->getConsultants();
        $this->data['totalRow'] = count($this->data['consultants']);
        $this->data['team_leads'] = $this->mu->getTableData(T_USERS,['type'=>'team_lead']);
        $this->processViewData('admin/consultant-list', 'Department List');
    }

    public function facility()
    {
        $this->data['facilities'] = $this->mu->getTableData(T_FACILITIES);
        $this->data['totalRow'] = count($this->data['facilities']);
        $this->processViewData('admin/list-facility', 'Facility List');
    }

    public function addFacility()
    {
        if ($this->input->post('submit')) {
            $fac_name = $this->input->post('fac_name', true);
            if (!empty($fac_name)) {
                $this->mu->insertIntoTable(T_FACILITIES, [
                    'name' => $fac_name,
                    'created_at' => CURR_DATETIME
                ]);

                $this->session->set_flashdata('success-msg', 'Facility Saved');
                redirect('admin/Features/facility');
            } else {
                $this->session->set_flashdata('error-msg', 'Please fill missing field');
            }
        }
    }

    public function facilityLocationManager()
    {
        $this->data['managers'] = $this->mu->getFacilityManagers();
        $this->data['locations'] = $this->mu->getTableData(T_LOCATIONS);
        $this->data['totalRow'] = count($this->data['managers']);
        $this->data['facilities'] = $this->mu->getTableData(T_FACILITIES);
        $this->processViewData('admin/list-facility-location-manager', 'Facility Managers');
    }

    public function addFacilityLocationManager()
    {
        $first_name = $this->input->post('first_name', true);
        $last_name = $this->input->post('last_name', true);
        $email = $this->input->post('email', true);
        $password = $this->input->post('password');
        $phone = $this->input->post('phone');
        $fac_id = (int) $this->input->post('facility_id');

        $user_id = $this->mu->insertIntoTable(T_USERS, [
            'username' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'type' => 'facility_location_manager',
            'phone' => $phone,
            'created_at' => CURR_DATETIME
        ]);

        $this->mu->insertIntoTable(T_FACILITY_LOCATION_MANAGERS, [
            'user_id' => $user_id,
            'facility_id' => $fac_id,
            'created_at' => CURR_DATETIME
        ]);

        $this->session->set_flashdata('success-msg', 'Project Manager Added');
        redirect('admin/Features/facilityLocationManager');
    }

    public function superUsers()
    {
        $facility_l_id = $this->mu->getTableRow(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $this->userData['user_id']]);


        $this->db->select('*')
            ->from(T_USERS . ' AS u')
            ->join(T_SUPER_USERS . ' AS su', 'su.user_id=u.user_id', 'LEFT')
            ->join(T_LOCATIONS . ' AS d', 'd.location_id=su.location_id', 'LEFT');
        if ($this->session->userdata('type') == 'facility_location_manager') {

            if (!empty($facility_l_id)) {
                $this->db->where('d.location_id', $facility_l_id->location_id);
                //$this->db->where('s.facility_id', $facility_id->facility_id);
            }
        }
        $this->data['susers'] = $this->db->where('u.type', 'super_user')
            ->get()->result();
        $this->data['locations'] = $this->mu->getTableData(T_LOCATIONS);
        $this->data['totalRow'] = count($this->data['susers']);
        $this->data['depts'] = $this->mu->getTableData(T_DEPTS);
        $this->processViewData('admin/list-super-users', 'Super Users');
    }

    public function addSuperUser()
    {
        $first_name = $this->input->post('first_name', true);
        $last_name = $this->input->post('last_name', true);
        $username = $last_name . "." . $first_name;
        $password = $this->input->post('password');
        $shift = $this->input->post('shift', true);
        $location = (int) $this->input->post('location');

        $user_id = $this->mu->insertIntoTable(T_USERS, [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'type' => 'super_user',
            'created_at' => CURR_DATETIME
        ]);

        $this->mu->insertIntoTable(T_SUPER_USERS, [
            'user_id' => $user_id,
            'location_id' => $location,
            'shift' => $shift,
            'created_at' => CURR_DATETIME
        ]);

        $this->session->set_flashdata('success-msg', 'Super User Added');
        redirect('admin/Features/superUsers');
    }

    public function addSuDept()
    {
        if ($this->input->post('submit')) {
            $user_id = intval($this->input->post('user_id'));
            $dept_id = intval($this->input->post('dept'));

            $su = $this->mu->getTableRow(T_SUPER_USERS, ['user_id' => $user_id]);
            if (!empty($su)) {
                $this->mu->updateTable(T_SUPER_USERS, ['dept_id' => $dept_id], ['user_id' => $user_id]);
                $this->session->set_flashdata('success-msg', 'Super User assigned successfully');
                redirect('admin/Features/superUsers');
            }
        }
    }

    public function locations()
    {
        $this->data['locations'] = $this->mu->getLocations();
        $this->data['managers'] = $this->mu->getFacilityManagers();
        $this->data['depts'] = $this->mu->getTableData(T_DEPTS);
        $this->data['facs'] = $this->mu->getTableData(T_FACILITIES);
        $this->data['totalRow'] = count($this->data['locations']);
        $this->processViewData('admin/list-locations', 'Locations');
    }

    public function addLocations()
    {
        $location_name = $this->input->post('location_name', true);
        $location_type = $this->input->post('location_type', true);
        $fac_id = intval($this->input->post('fac_id'));
        $p_manager_uid = (int) $this->input->post('project_manager');
        if ($this->input->post('submit')) {
            $location_id = $this->mu->insertIntoTable(T_LOCATIONS, ['name' => $location_name, 'type' => $location_type, 'facility_id' => $fac_id, 'created_at' => CURR_DATETIME]);
            $this->mu->insertIntoTable(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $p_manager_uid, 'location_id' => $location_id, 'created_at' => CURR_DATETIME]);
            $this->session->set_flashdata('success-msg', 'Location Added successfully');
            redirect('admin/Features/locations');
        }
    }

    public function shifts($location_id = 0)
    {

        $facility_l_id = $this->mu->getTableRow(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $this->userData['user_id']]);
        $this->db->select('GROUP_CONCAT(DISTINCT l.name) as location_names,f.name as facility_name,GROUP_CONCAT(DISTINCT l.location_id) as location_ids')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->join(T_FACILITIES . ' AS f', 'f.fac_id=l.facility_id', 'LEFT')
            ->group_by('f.fac_id')
            ->order_by('l.name');

        if ($this->session->userdata('type') == 'facility_location_manager') {

            if (!empty($facility_l_id)) {
                $this->db->where('l.location_id', $facility_l_id->location_id);
                //$this->db->where('s.facility_id', $facility_id->facility_id);
            }
        }
        $this->data['events'] = $this->db->get()->result();
        $this->data['facility_name'] = $this->db->select('f.name,l.name as location')
            ->from(T_LOCATIONS . ' AS l')
            ->join(T_FACILITIES . ' AS f', 'f.fac_id=l.facility_id')
            ->where('l.location_id', $location_id)
            ->get()->row();

        $this->data['location_id'] = $location_id;
        $this->data['facs'] = $this->mu->getTableData(T_FACILITIES);
        $this->data['modules'] = $this->mu->getTableData(T_MODULES);
        $this->data['red'] = $this->mu->getTableData(T_SHIFTS, ['location_id' => $location_id, 'color' => 'red']);
        $this->data['green'] = $this->mu->getTableData(T_SHIFTS, ['location_id' => $location_id, 'color' => 'green']);
        $this->data['yellow'] = $this->mu->getTableData(T_SHIFTS, ['location_id' => $location_id, 'color' => 'yellow']);
        $this->data['total'] = $this->mu->countTableRow(T_SHIFTS, ['location_id' => $location_id]);
        $this->data['jump_date'] = $this->session->flashdata('jump_date');
        $this->data['locations_list'] = $this->db->select('l.location_id,l.name')
            ->distinct()
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 's.location_id=l.location_id', 'left')
            ->order_by('l.location_id', 'AESC')->get()->result();

        $this->db->select('s.*,l.name,u.first_name,u.last_name,u.type')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->join(T_A_CONSULTANTS . ' AS ac', 'ac.event_id=s.event_id', 'LEFT')
            ->join(T_A_SUPER_USERS . ' AS su', 'su.event_id=s.event_id', 'LEFT')
            ->join(T_USERS . ' AS u', 'u.user_id=ac.user_id OR   u.user_id=su.user_id', 'LEFT');

        if ($this->session->userdata('type') == 'facility_location_manager') {

            if (!empty($facility_l_id)) {
                $this->db->where('s.location_id', $facility_l_id->location_id);
            }
        }
        $this->data['shifts'] = $this->db->get()->result();
        $this->processViewData('admin/list-shift', 'Shifts');
    }

    public function addEvent()
    {
        if ($this->input->post('submit')) {
            $module1 = $module2 = $pref_skill1 = $pref_skill2 = $demeanor = $count_superuser = $count_consultant = 0;
            $color = "";
            $consultants = "";
            $facility_id = (int) $this->input->post('facility_id');
            $location_id = (int) $this->input->post('location');
            $num_of_su = $this->input->post('num_of_su');
            $start_date = $this->input->post('start_date', true);
            $end_date = $this->input->post('start_date', true);
            $start_time = $this->input->post('start_time', true);
            $hour = $this->input->post('end_time', true);
            $end_time = date('H:i:s', strtotime($start_time) + ($hour * 3600));

            $start_date_time = $start_date . " " . $start_time . ":00";
            $end_date_time = $end_date . " " . $end_time . ":00";
            $shift = $this->input->post('shift', true);
            $num_of_const = (int) $this->input->post('num_of_const');
            $occurance = (int) $this->input->post('occurance');

            $super_users = $this->mu->getSuperUsersForShift((int)$num_of_su[0], $shift, $location_id);
            //$availability_su = $num_of_su-$this->mu->countTableRow(T_SUPER_USERS, ['shift' => $shift,'location_id'=>$location_id]);

            if ($num_of_const != 0) {
                $module1 = (int) $this->input->post('pref_module1');
                $module2 = (int) $this->input->post('pref_module2');
                $module1_name = $this->mu->getTableRow(T_MODULES, ['module_id' => $module1])->name;
                $module2_name = $this->mu->getTableRow(T_MODULES, ['module_id' => $module2])->name;
                $pref_skill1 = (int) $this->input->post('pref_skill1');
                $pref_skill2 = (int) $this->input->post('pref_skill2');
                $demeanor = (int) $this->input->post('demeanor');

                $consultants = $this->mu->getConsultantForShift($num_of_const, $shift, $module1_name, $module2_name, $pref_skill1, $pref_skill2, $demeanor, $location_id);
                //$availability_const = $num_of_const - $this->mu->checkConsultantAvailable($shift, $module1_name, $module2_name, $pref_skill1, $pref_skill2, $demeanor, $location_id);
            }
            //setting event color
            //0=requirement filled, <0 = available more than requirement, >0= shortage
            $event_id = $this->insertEvent($facility_id, $location_id, (int) $num_of_su[0], $num_of_const, $shift, $module1, $module2, $pref_skill1, $pref_skill2, $demeanor, $start_date_time, $end_date_time);
            $this->addEventUsers((int) $num_of_su[0], $super_users, $event_id, $num_of_const, $consultants);

            if ($occurance != 0) {
                for ($i = 1; $i < $occurance; $i++) {
                    $super_users = $this->mu->getSuperUsersForShift($num_of_su[$i], $shift, $location_id);
                    $start_date_time = date("Y-m-d H:i:s", strtotime('+1 days', strtotime($start_date_time)));
                    $end_date_time = date("Y-m-d H:i:s", strtotime('+1 days', strtotime($end_date_time)));
                    if ((int) $num_of_su[$i] != 0) {
                        $event_id = $this->insertEvent($facility_id, $location_id, (int) $num_of_su[$i], $num_of_const, $shift, $module1, $module2, $pref_skill1, $pref_skill2, $demeanor, $start_date_time, $end_date_time);
                        $this->addEventUsers((int) $num_of_su[$i], $super_users, $event_id, $num_of_const, $consultants);
                    }
                }
            }

            $this->session->set_flashdata('success-msg', 'Shift(s) Added successfully');
            redirect('admin/Features/shifts/' . $location_id);
        }
    }

    private function insertEvent($facility_id, $location_id, $num_of_su, $num_of_const, $shift, $module1, $module2, $pref_skill1, $pref_skill2, $demeanor, $start_date_time, $end_date_time)
    {
        return $this->mu->insertIntoTable(T_SHIFTS, [
            'facility_id' => $facility_id,
            'location_id' => $location_id,
            'number_su' => $num_of_su,
            'number_const' => $num_of_const,
            'shift' => $shift,
            'module_1' => $module1,
            'module_2' => $module2,
            'skill_1' => $pref_skill1,
            'skill_2' => $pref_skill2,
            'demeanor' => $demeanor,
            'start_time' => $start_date_time,
            'end_time' => $end_date_time,
            'created_at' => CURR_DATETIME
        ]);
    }

    private function addEventUsers($num_of_su, $super_users, $event_id, $num_of_const = 0, $consultants = null)
    {
        foreach ($super_users as $s) {
            $this->mu->insertIntoTable(T_A_SUPER_USERS, ['user_id' => $s->user_id, 'event_id' => $event_id]);
        }
        if ($num_of_const != 0) {
            foreach ($consultants as $c) {
                $this->mu->insertIntoTable(T_A_CONSULTANTS, ['user_id' => $c->user_id, 'event_id' => $event_id]);
            }
            $count_consultant = $this->mu->countTableRow(T_A_CONSULTANTS, ['event_id' => $event_id]);
            $availability_const = $num_of_const - $count_consultant;
        }

        $count_superuser = $this->mu->countTableRow(T_A_SUPER_USERS, ['event_id' => $event_id]);
        $availability_su = $num_of_su - $count_superuser;

        if ($num_of_const == 0) {
            if ($availability_su == 0) {
                $color = 'green';
            } elseif ($availability_su > 0 && $availability_su < $num_of_su) {
                $color = 'yellow';
            } elseif ($count_superuser == 0) {
                $color = 'red';
            }
        } else {
            if (($availability_const == 0 || $availability_const < 0) && ($availability_su == 0 || $availability_su < 0)) {
                $color = 'green';
            } elseif ($availability_const > 0 || $availability_su > 0) {
                $color = '#yellow';
            } elseif ($count_superuser == 0 && $count_consultant == 0) {
                $color = 'red';
            }
        }

        $this->mu->updateTable(T_SHIFTS, ['color' => $color], ['event_id' => $event_id]);
    }

    public function updateEvent()
    {
        $event_id = (int) $this->input->post('event_id');
        $event = $this->mu->getTableRow(T_SHIFTS, ['event_id' => $event_id]);
        $this->session->set_flashdata('jump_date', $event->start_time);

        $consultants = $this->input->post('consultants');
        $super_users = $this->input->post('super_users');

        $this->mu->deleteTableRow(T_A_CONSULTANTS, ['event_id' => $event_id]);
        $this->mu->deleteTableRow(T_A_SUPER_USERS, ['event_id' => $event_id]);

        if (is_array($super_users)) {
            for ($i = 0; $i < count($super_users); $i++) {
                $this->mu->insertIntoTable(T_A_SUPER_USERS, ['user_id' => $super_users[$i], 'event_id' => $event_id]);
            }
        }
        if (is_array($consultants)) {
            for ($i = 0; $i < count($consultants); $i++) {
                $this->mu->insertIntoTable(T_A_CONSULTANTS, ['user_id' => $consultants[$i], 'event_id' => $event_id]);
            }
        }


        $count_consultant = $this->mu->countTableRow(T_A_CONSULTANTS, ['event_id' => $event_id]);
        $count_superuser = $this->mu->countTableRow(T_A_SUPER_USERS, ['event_id' => $event_id]);

        $availability_const = $event->number_const - $count_consultant;
        $availability_su = $event->number_su - $count_superuser;
        $color = "";

        //setting event color
        //0=requirement filled, <0 = available more than requirement, >0= shortage
        if ($event->number_const == 0) {
            if ($availability_su == 0) {
                $color = 'green';
            } elseif ($availability_su > 0 && $availability_su < $event->number_su) {
                $color = 'yellow';
            } elseif ($count_superuser == 0) {
                $color = 'red';
            }
        } else {
            if (($availability_const == 0 || $availability_const < 0) && ($availability_su == 0 || $availability_su < 0)) {
                $color = 'green';
            } elseif ($availability_const > 0 || $availability_su > 0) {
                $color = 'yellow';
            } elseif ($count_superuser == 0 && $count_consultant == 0) {
                $color = 'red';
            }
        }

        $this->mu->updateTable(T_SHIFTS, ['color' => $color], ['event_id' => $event_id]);

        redirect('admin/Features/shifts/' . $event->location_id);
    }

    //delete functions
    public function deleteFacLocationManager($user_id = 0)
    {
        if ($user_id != 0) {
            $this->mu->deleteTableRow(T_USERS, ['user_id' => $user_id, 'type' => 'facility_location_manager']);
            $this->mu->deleteTableRow(T_FACILITY_LOCATION_MANAGERS, ['user_id' => $user_id]);
            $this->mu->deleteTableRow(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $user_id]);

            redirect('admin/Features/facilityLocationManager');
        }
    }

    public function deleteSuperUser($user_id = 0)
    {
        if ($user_id != 0) {
            $this->mu->deleteTableRow(T_USERS, ['user_id' => $user_id, 'type' => 'super_user']);
            $this->mu->deleteTableRow(T_SUPER_USERS, ['user_id' => $user_id]);
            $this->mu->deleteTableRow(T_A_SUPER_USERS, ['user_id' => $user_id]);

            redirect('admin/Features/superUsers');
        }
    }

    // public function deleteShift($event_id = 0) {
    //     if ($event_id != 0) {
    //         $this->mu->deleteTableRow(T_SHIFTS, ['event_id' => $event_id]);
    //         $this->mu->deleteTableRow(T_A_CONSULTANTS, ['event_id' => $event_id]);
    //         $this->mu->deleteTableRow(T_A_SUPER_USERS, ['event_id' => $event_id]);

    //         redirect('admin/Features/shifts');
    //     }
    // }

    public function deleteLocation($location_id = 0)
    {
        if ($location_id != 0) {
            $this->mu->deleteTableRow(T_LOCATIONS, ['location_id' => $location_id]);
            $this->mu->deleteTableRow(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['location_id' => $location_id]);

            redirect('admin/Features/locations');
        }
    }

    public function deleteDept($dept_id = 0)
    {
        if ($dept_id != 0) {
            $this->mu->deleteTableRow(T_DEPTS, ['dept_id' => $dept_id]);
            redirect('admin/Features/deptList');
        }
    }

    public function addAppadmin()
    {
        if ($this->userType == 'app_admin') {
            $username = $this->input->post('username', true);
            $first_name = $this->input->post('first_name', true);
            $last_name = $this->input->post('last_name', true);
            $password = $this->input->post('password');
            $phone = (int) $this->input->post('phone');
            $this->mu->insertIntoTable(T_USERS, [
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'type' => 'app_admin',
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        }
    }

    public function appAdmin()
    {
        if ($this->userType == 'app_admin') {
            $this->data['app_admin'] = $this->mu->getTableData(T_USERS, ['type' => 'app_admin']);
            $this->data['totalRow'] = count($this->data['app_admin']);

            $this->processViewData('admin/list-app-admin', 'Super Users');
        }
    }

    public function updatePassword()
    {
        $prev_pass = $this->input->post('prev_pass');
        $user = $this->mu->getTableRow(T_USERS, ['user_id' => $this->userId]);

        if (password_verify($prev_pass, $user->password)) {
            $new_password = $this->input->post('new_pass');
            $c_new_password = $this->input->post('c_new_pass');
            if ($new_password == $c_new_password) {
                $this->mu->updateTable(T_USERS, ['password' => password_hash($new_password, PASSWORD_BCRYPT)], ['user_id' => $this->userId]);
                $this->session->set_flashdata('success-msg', 'Password Change Successful');
            } else {
                $this->session->set_flashdata('success-msg', 'Verify Password field did not match');
            }
            redirect('admin/Features/changeUserPassword');
        }
    }

    public function changeUserPassword()
    {
        $this->processViewData('forms/change-password', 'Change Password');
    }

    public function addFacPrMng()
    {
        if ($this->userType == 'app_admin') {
            $username = $this->input->post('username', true);
            $first_name = $this->input->post('first_name', true);
            $last_name = $this->input->post('last_name', true);
            $password = $this->input->post('password');
            $phone = (int) $this->input->post('phone');
            $this->mu->insertIntoTable(T_USERS, [
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'type' => 'facility_project_manager',
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            redirect('admin/Features/facilityProjectManager');
        }
    }

    public function facilityProjectManager()
    {
        if ($this->userType == 'app_admin') {
            $this->data['fpm'] = $this->mu->getTableData(T_USERS, ['type' => 'facility_project_manager']);
            $this->data['totalRow'] = count($this->data['fpm']);

            $this->processViewData('admin/list-facility-project-manager', 'Facility Project Managers');
        }
    }

    public function copyEvents()
    {
        $copy_from = (int)$this->input->post('copy_from');
        $copy_to = (int)$this->input->post('copy_to');
        $facility_id = $this->mu->getTableRow(T_LOCATIONS, ['location_id' => $copy_to])->facility_id;
        $day = $this->input->post('days');
        $night = $this->input->post('nights');
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $shifts = "";
        if ($day == 'on' && $night == 'on') {
            $shifts = $this->mu->getTableData(T_SHIFTS, ['location_id' => $copy_from]);
        } elseif ($day == 'on' && $night == null) {
            $shifts = $this->mu->getTableData(T_SHIFTS, ['location_id' => $copy_from, 'shift' => 'day']);
        } else {
            $shifts = $this->mu->getTableData(T_SHIFTS, ['location_id' => $copy_from, 'shift' => 'night']);
        }

        if ($start_time == "" && $end_time == "" && $copy_to != null) {
            foreach ($shifts as $s) {
                $this->mu->insertIntoTable(T_SHIFTS, [
                    'facility_id' => $facility_id,
                    'location_id' => $copy_to,
                    'number_su' => $s->number_su,
                    'number_const' => $s->number_const,
                    'shift' => $s->shift,
                    'module_1' => $s->module_1,
                    'module_2' => $s->module_2,
                    'skill_1' => $s->skill_1,
                    'skill_2' => $s->skill_2,
                    'demeanor' => $s->demeanor,
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'color' => $s->color,
                ]);
            }
        } else {
            foreach ($shifts as $s) {
                $start_date_time = date('Y-m-d', strtotime($s->start_time)) . " " . $start_time . ":00";
                $end_date_time = date('Y-m-d', strtotime($s->end_time)) . " " . $end_time . ":00";

                $this->mu->insertIntoTable(T_SHIFTS, [
                    'facility_id' => $facility_id,
                    'location_id' => $copy_to,
                    'number_su' => $s->number_su,
                    'number_const' => $s->number_const,
                    'shift' => $s->shift,
                    'module_1' => $s->module_1,
                    'module_2' => $s->module_2,
                    'skill_1' => $s->skill_1,
                    'skill_2' => $s->skill_2,
                    'demeanor' => $s->demeanor,
                    'start_time' => $start_date_time,
                    'end_time' => $end_date_time,
                    'color' => $s->color,
                ]);
            }
        }
    }

    public function exportShift()
    {
        $file_name = 'shift_export_on_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/csv;");

        // // get data 
        $shift_data =  $this->db->select("f.name AS facility,l.name AS location,s.start_time,s.end_time")
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->join(T_FACILITIES . ' AS f', 'f.fac_id=s.facility_id', 'LEFT')
            ->get()->result_array();

        // // file creation 
        $file = fopen('php://output', 'w');

        $header = array("Facility", "Location", "Start_time", "End_time");
        fputcsv($file, $header);
        foreach ($shift_data as $key => $value) {
            fputcsv($file, $value);
        }
        fclose($file);
        exit;
    }

    public function teamLead()
    {
        if ($this->userType == 'app_admin') {
            $this->data['team_lead'] = $this->mu->getTableData(T_USERS, ['type' => 'team_lead']);
            $this->data['totalRow'] = count($this->data['team_lead']);

            $this->processViewData('admin/list-team-lead', 'Team Lead');
        }
    }

    public function addTeamLead()
    {
        if ($this->userType == 'app_admin') {
            $username = $this->input->post('username', true);
            $first_name = $this->input->post('first_name', true);
            $last_name = $this->input->post('last_name', true);
            $password = $this->input->post('password');
            $phone = (int) $this->input->post('phone');
            $this->mu->insertIntoTable(T_USERS, [
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'type' => 'team_lead',
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            redirect('admin/Features/teamLead');
        }
    }
}
