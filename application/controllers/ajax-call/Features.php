<?php

if (!defined('BASEPATH'))
    exit('Not allowed');

class Features extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->result = [
            'success' => false,
            'msg' => '',
            'data' => []
        ];


        if ($this->isAjaxRequest === false) {
            $this->jsonOutput([
                'success' => false,
                'msg' => 'Access Forbidden'
            ]);
        }
        $this->load->model('Model_Users', 'mu');

        if (!$this->isLoggedIn && $this->userType != 'app_admin') {
            $this->session->set_flashdata('error-msg', 'You do not have permission to view this page.');
            redirect('Auth/login');
        }
    }

    public function saveDemeanor()
    {
        $rating = intval($this->input->post('rating'));
        $user_id = intval($this->input->post('user_id'));
        if ($this->mu->countTableRow(T_CONSULTANTS, ['user_id' => $user_id]) > 0) {
            $this->mu->updateTable(T_CONSULTANTS, ['demeanor' => $rating], ['user_id' => $user_id]);
        }
    }

    /**
     * Update user profile image
     */
    public function user_profile_update()
    {
        if (file_exists($_FILES['user_image']['tmp_name']) || is_uploaded_file($_FILES['user_image']['tmp_name'])) {
            $userInfo = $this->mu->getTableRow(T_USERS, ['user_id' => $this->userId], 'propic');
            if (!empty($userInfo)) {
                $tempResult = $this->processFileUpload('user_image', PROFILE_IMAGE_PATH, 'propic', $userInfo->propic);

                if ($tempResult['success']) {
                    if ($this->mu->updateTable(T_USERS, ['propic' => $tempResult['name']], ['user_id' => $this->userId])) {
                        $this->result['success'] = true;
                    } else {
                        $this->result['msg'] = 'Error! Fail to update database.';
                    }
                }
                $this->result['msg'] = $tempResult['msg'];
            }
        }

        $this->jsonOutput($this->result);
    }

    public function getSecondModule()
    {
        $mod1 = $this->input->post('module1');
        $modules = $this->db->select('module_id,name')
            ->from(T_MODULES)
            ->where('name!=', $mod1)->get()->result_array();

        $this->result = [
            'success' => true,
            'msg' => '',
            'data' => $modules
        ];
        $this->jsonOutput($this->result);
    }

    public function getThirdModule()
    {
        $mod1 = $this->input->post('module1', true);
        $mod2 = $this->input->post('module2', true);
        $modules = $this->db->select('module_id,name')
            ->from(T_MODULES)
            ->where('name!=', $mod1)->where('name!=', $mod2)->get()->result_array();

        $this->result = [
            'success' => true,
            'msg' => '',
            'data' => $modules
        ];
        $this->jsonOutput($this->result);
    }

    public function saveRating()
    {
        $rating_num = intval($this->input->post('rating_num'));
        $rating_val = intval($this->input->post('rating_val'));
        $user_id = intval($this->input->post('user_id'));

        $this->mu->updateTable(T_CONSULT_MODULES, ['rating' . $rating_num => $rating_val], ['user_id' => $user_id]);
    }

    public function saveTeamLead(){
        $consultant_uid = intval($this->input->post('consultant_uid'));
        $team_lead_uid = intval($this->input->post('team_lead_uid'));
       

        $this->mu->updateTable(T_CONSULTANTS, ['team_lead_uid' => $team_lead_uid], ['user_id' => $consultant_uid]);
    }

    public function getManagersOfFacility()
    {
        $fac_id = (int) $this->input->post('fac_id');
        $managers_list = $this->db->select('u.first_name,u.last_name,u.user_id,m.manager_id')
            ->from(T_USERS . ' AS u')
            ->join(T_FACILITY_LOCATION_MANAGERS . ' AS m', 'm.user_id=u.user_id', 'LEFT')
            ->where('u.type', 'facility_location_manager')
            ->where('m.facility_id', $fac_id)
            ->get()->result();
        $this->result = [
            'success' => true,
            'msg' => '',
            'data' => $managers_list
        ];
        $this->jsonOutput($this->result);
    }

    public function loadShifts($location = 0)
    {
        $data = $events = [];
        if ($this->userType == 'super_user') {
            $events = $this->mu->getTableData(T_A_SUPER_USERS, ['user_id' => $this->userId], 'event_id');
        }
        $this->db->select('s.*,l.name,GROUP_CONCAT(`u`.`first_name`, `u`.`last_name`  SEPARATOR "\n") AS u_name,count(su.event_id) as user_count')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->join(T_A_SUPER_USERS . ' AS su', 'su.event_id=s.event_id', 'LEFT')
            ->join(T_USERS . ' AS u', 'u.user_id=su.user_id', 'LEFT')
            ->group_by('s.event_id');

        if ($this->userType == 'super_user') {
            $this->db->where_in('s.event_id', array_column($events, 'event_id'));
        } else {
            $this->db->where('s.location_id', $location);
        }
        $result = $this->db->get()->result();
        foreach ($result as $row) {
            $data[] = array(
                'id' => $row->event_id,
                'title' => $row->u_name != null ? (date('Ha', strtotime($row->start_time)) . '-' .
                    date('Ha', strtotime($row->end_time)) . "\n" . $row->u_name . "\n" .
                    $row->user_count . "/" . $row->number_su) : (date('Ha', strtotime($row->start_time)) . '-' .
                    date('Ha', strtotime($row->end_time)) . "\n" . "No Super User Assigned"),
                'start' => $row->start_time,
                'end' => $row->end_time,
                'color' => $row->color,
                'textColor' => $row->color == 'yellow' ? 'black' : 'white',
                'description' => ''
            );
        }
        $this->session->set_flashdata('jump_date', $result[0]->start_time);
        $this->jsonOutput($data);
    }

    public function getLocationOfFacility()
    {
        $fac_id = (int) $this->input->post('fac_id');
        $location_list = $this->db->select('l.*')
            ->from(T_LOCATIONS . ' AS l')
            ->join(T_FACILITIES . ' AS f', 'f.fac_id=l.facility_id')
            ->where('f.fac_id', $fac_id)
            ->get()->result();

        $this->result = [
            'success' => true,
            'msg' => '',
            'data' => $location_list
        ];
        $this->jsonOutput($this->result);
    }

    public function getEventDetails()
    {
        $consultants = $super_users = "";
        $event_id = (int) $this->input->post('id');
        $event = $this->mu->getTableRow(T_SHIFTS, ['event_id' => $event_id]);
        if ($event->number_const > 0) {
            $module1_name = $this->mu->getTableRow(T_MODULES, ['module_id' => $event->module_1])->name;
            $module2_name = $this->mu->getTableRow(T_MODULES, ['module_id' => $event->module_2])->name;
            $this->db->where('c.demeanor', $event->demeanor);
            $this->db->where('c.shift', $event->shift);
            $this->db->where('c.location_id', $event->location_id);

            $consultants = $this->db->select('c.*,u.first_name,u.last_name,u.user_id')
                ->from(T_CONSULTANTS . ' AS c')
                ->join(T_CONSULT_MODULES . ' AS cm', 'c.user_id=cm.user_id', 'LEFT')
                ->join(T_USERS . ' AS u', 'u.user_id=c.user_id', 'LEFT')
                ->join(T_DEPTS . ' AS d', 'd.dept_id=c.department', 'LEFT')
                ->join(T_LOCATIONS . ' AS l', 'l.type=d.dept_id', 'LEFT')
                ->where('cm.module1', $module1_name)
                ->where('cm.module2', $module2_name)
                ->or_where('cm.module3', $module2_name)
                ->where('cm.rating1>=', $event->skill_1)
                ->where('cm.rating2>=', $event->skill_2)
                ->where('l.location_id', $event->location_id)
                ->where('shift', $event->shift)
                ->order_by('c.consultant_id')
                ->get()->result();
        }

        $super_users = $this->db->select('super_users.*,users.first_name,users.last_name,users.user_id')
            ->from(T_SUPER_USERS)
            ->join(T_USERS, 'users.user_id=super_users.user_id')
            ->where('shift', $event->shift)
            ->where('location_id', $event->location_id)
            ->order_by('su_id')
            ->get()->result();


        $shift = $this->db->select('s.*,l.name,u.first_name,u.last_name,u.type,u.user_id')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->join(T_A_CONSULTANTS . ' AS ac', 'ac.event_id=s.event_id', 'LEFT')
            ->join(T_A_SUPER_USERS . ' AS su', 'su.event_id=s.event_id', 'LEFT')
            ->join(T_USERS . ' AS u', 'u.user_id=ac.user_id OR   u.user_id=su.user_id', 'LEFT')
            ->where('s.event_id', $event_id)
            ->get()->result();
        $data = [
            'consultants' => $consultants,
            'super_users' => $super_users,
            'shift' => $shift
        ];

        $this->result = [
            'success' => true,
            'msg' => '',
            'data' => $data
        ];
        $this->jsonOutput($this->result);
    }

    public function updateFacLocMng()
    {
        $location_id = $this->input->post('location_id');
        $phone = (int) $this->input->post('phone');
        $user_id = (int) $this->input->post('user_id');
        $facility = (int)$this->input->post('facility_id');

        $this->mu->updateTable(T_USERS, ['phone' => $phone], ['user_id' => $user_id]);
        $this->mu->deleteTableRow(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $user_id]);
        if (!empty($location_id)) {
            //updating user locations
            for ($i = 0; $i < count($location_id); $i++) {
                $this->mu->insertIntoTable(T_FACILITY_LOCATION_MANAGERS_LOCATIONS, ['user_id' => $user_id, 'location_id' => $location_id[$i], 'created_at' => CURR_DATETIME]);
            }
        }
        if (!empty($facility)) {
            $this->mu->updateTable(T_FACILITY_LOCATION_MANAGERS, ['facility_id' => $facility], ['user_id' => $user_id]);
        }
    }

    public function updateSuser()
    {
        $user_id = (int)$this->input->post('user_id');
        $location_id = (int)$this->input->post('location_id');
        $shift = $this->input->post('shift', true);
        $this->mu->updateTable(T_SUPER_USERS, ['location_id' => $location_id, 'shift' => $shift], ['user_id' => $user_id]);
    }

    public function deleteShift()
    {
        $shifts = $this->input->post('shifts');
        for ($i = 0; $i < count($shifts); $i++) {
            $this->mu->deleteTableRow(T_SHIFTS, ['event_id' => $shifts[$i]]);
            $this->mu->deleteTableRow(T_A_CONSULTANTS, ['event_id' => $shifts[$i]]);
            $this->mu->deleteTableRow(T_A_SUPER_USERS, ['event_id' => $shifts[$i]]);
        }
    }

    public function getDashboardInfo()
    {
        $facility = $this->input->post('facility');
        $events = $this->db->select('l.location_id, l.name AS location_name, s.event_id, s.shift, COUNT(a.event_id) AS n_events, number_su')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_A_SUPER_USERS . ' AS a', 'a.event_id = s.event_id', 'LEFT')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id = s.location_id', 'LEFT')
            ->where('s.facility_id', $facility)
            ->group_by('s.event_id')
            ->get()->result();


        $final_count = [];
        foreach ($events as $e => $v) {

            if (!array_key_exists($v->location_id, $final_count)) {
                $final_count[$v->location_id] = [
                    'location_id' => $v->location_id,
                    'location_name' => $v->location_name,
                    'completed' => 0,
                    'partial_day' => 0,
                    'partial_night' => 0,
                    'unscheduled_day' => 0,
                    'unscheduled_night' => 0
                ];
            }

            if ($v->number_su - $v->n_events == 0) {
                $final_count[$v->location_id]['completed']++;
            } elseif ($v->number_su - $v->n_events > 0 && $v->number_su > $v->number_su - $v->n_events && $v->shift == 'day') {
                $final_count[$v->location_id]['partial_day']++;
            } elseif ($v->number_su - $v->n_events > 0 && $v->number_su > $v->number_su - $v->n_events && $v->shift == 'night') {
                $final_count[$v->location_id]['partial_night']++;
            } elseif ($v->number_su - $v->n_events == $v->number_su && $v->shift == 'day') {
                $final_count[$v->location_id]['unscheduled_day']++;
            } else {
                $final_count[$v->location_id]['unscheduled_night']++;
            }
        }

        $assigned_su = $this->db->select('s.event_id,count(su.event_id) as count_su,s.facility_id,s.number_su,s.shift,l.name as location')
            ->from(T_SHIFTS . ' AS s')
            ->join(T_A_SUPER_USERS . ' AS su', 's.event_id=su.event_id', 'LEFT')
            ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
            ->where('s.facility_id', $facility)
            ->group_by('s.event_id')
            ->get()->result();

        $complete = $partial = $unscheduled = $locations = [];

        foreach ($assigned_su as $su) {
            if ($su->number_su - $su->count_su == 0) {
                array_push($complete, $su->event_id);
            } elseif (($su->number_su - $su->count_su) == $su->number_su) {
                array_push($unscheduled, $su->event_id);
            } else {
                array_push($partial, $su->event_id);
            }
        }

        $dataPoints = array(
            array("label" => "completed", "y" => count($complete)),
            array("label" => "partial", "y" => count($partial)),
            array("label" => "unscheduled", "y" => count($unscheduled))
        );
        $this->jsonOutput(['datapoints' => $dataPoints, 'events' => array_values($final_count)]);
    }

    public function checkLocation()
    {
        $location = $this->input->post('location_name', true);
        if ($this->mu->countTableRow(T_LOCATIONS, ['name' => $location]) > 0) {
            $this->jsonOutput(['result' => true]);
        } else {
            $this->jsonOutput(['result' => false]);
        }
    }

    public function getSupportNames()
    {
        $shift = $this->input->post('shift', true);
        $details = $this->mu->getTableRow(T_SHIFTS, ['event_id' => $shift[0]]);
        $max_employee = $details->number_su + $details->number_const;
        $selected_users = $this->db->select('u.user_id')
            ->from(T_A_SUPER_USERS . ' AS su')
            ->join(T_USERS . ' AS u', 'u.user_id=su.user_id', 'LEFT')
            ->where('su.event_id', $details->event_id)
            ->get()->result_array();

        $names = $this->db->select('u.first_name,u.last_name,u.user_id')
            ->from(T_SUPER_USERS . ' AS su')
            ->join(T_USERS . ' AS u', 'u.user_id=su.user_id', 'LEFT')
            ->where('su.location_id', $details->location_id)
            ->where('su.shift', $details->shift)
            ->get()->result();

        $this->jsonOutput(['data' => $names, 'selected_users' => $selected_users,'max_user'=>$max_employee]);
    }
}
