<?php

if (!defined('BASEPATH'))
    exit('Not allowed');

class Auth extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Users', 'mu');
        $this->load->library('encryption');
    }

    public function login()
    {
        if ($this->isLoggedIn) {
            redirect('auth/dashboard');
        }
        if ($this->input->post('login')) {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);

            $details = $this->mu->getTableRow(T_USERS, ['username' => $username]);
            if (!empty($details)) {
                if (password_verify($password, $details->password)) {
                    $this->session->set_flashdata('success-msg', 'Login Successful');
                    $userData = array(
                        'userId' => $details->user_id,
                        'username' => $details->username,
                        'first_name' => $details->first_name,
                        'last_name' => $details->last_name,
                        'type' => $details->type
                    );
                    $this->session->set_userdata($userData);


                    redirect('auth/dashboard');
                } else {
                    $this->session->set_flashdata('error-msg', 'Password did not match');
                }
            }
        }
        $this->processViewData('forms/login', 'Login');
    }

    public function userRegistration()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('fname', 'First name', 'required');
            $this->form_validation->set_rules('lname', 'Last name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error-msg', validation_errors());
                redirect('Auth/registration');
            }
            $email = $this->input->post('email', true);
            $user_type = 'consultant';
            $first_name = $this->input->post('fname', true);
            $last_name = $this->input->post('lname', true);

            $this->mu->insertIntoTable(T_USERS, [
                'username' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'type' => $user_type
            ]);

            $this->sendVerificationMail($email, $user_type, $first_name, $last_name);
            $this->session->set_flashdata('success-msg', 'A verification email is sent');
            redirect('Auth/userRegistration');
        }
        $this->processViewData('forms/registration', 'Registration | Hospital');
    }

    public function sendVerificationMail($email, $userType, $firstname, $lastname)
    {
        $this->load->library('parser');

        /**
         * Initialize array to generate the token
         * http://dikkha.com/login/verify?token=sdfsdfsdfsdf
         */
        $userTokenData = [
            'user' => $email,
            'expireAt' => time() + 259200, //3days
            'type' => 'registration',
            'usertype' => $userType
        ];
        $this->load->library('encryption');
        $newToken = urlencode($this->encryption->encrypt(serialize($userTokenData)));

        $content = $this->parser->parse('html_template/mailsuccess', [
            'user' => $firstname . " " . $lastname,
            'url' => base_url('login/verify') . '?token=' . $newToken
        ]);
        $this->load->library('emailservice');
        $this->emailservice->mail($email, $content);
    }

    /**
     * Verify user using email
     */
    public function userVerification()
    {
        $token = $this->input->get('token', true);
        $details = unserialize($this->encryption->decrypt($token));

        $user = $details['user'];
        $expire = $details['expireAt'];
        $type = $details['type'];
        $usertype = $details['usertype'];
        if ($user && $type && $expire) {
            if ($expire > time()) {
                $user = $this->mu->getTableRow(T_USERS, ['username' => $user]);
                if ($user->password == null) {
                    $this->mu->updateTable(T_USERS, ['verified' => 1], ['username' => $user->username]);
                    $this->setUserPassword($user->username);
                    exit();
                } else {
                    $this->data['msg'] = 'Invalid Request';
                    $this->errorFlag = false;
                }
            } else {
                $this->data['msg'] = 'Your validation mail is expired, you can get a new verification email by going to this : <a href="' . base_url('resend-verification-email') . '">' . 'link' . '</a>';
                $this->errorFlag = false;
            }
        }

        $this->data['success'] = $this->errorFlag;
        $this->data['type'] = 'user-verify';
        $this->processViewData('message/success', 'Verify User');
    }

    private function setUserPassword($username)
    {
        $this->data['username'] = $username;
        $this->processViewData('forms/_addPassword', 'Set Password');
    }

    public function setPassword()
    {
        $username = $this->input->post('username', true);

        $details = $this->mu->getTableRow(T_USERS, ['username' => $username]);
        if ($this->input->post('submit')) {
            if ($details->password == null && $details->verified == 1) {
                $this->form_validation->set_rules('password', 'Password', 'required');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');

                if ($this->form_validation->run() === false) {
                    $this->session->set_flashdata('error-msg', validation_errors());
                    $this->setUserPassword($username);
                } else {
                    $password = $this->input->post('password');
                    $cpassword = $this->input->post('cpassword');

                    if ($password === $cpassword) {
                        $password = password_hash($password, PASSWORD_BCRYPT);
                        $this->mu->updateTable(T_USERS, ['password' => $password], ['username' => $username]);

                        $this->session->set_flashdata('success-msg', 'password set successfully. Please login');
                        redirect('Auth/login');
                    }
                }
            }
        }
    }

    //    public function addConsultantInfo($user_id) {
    //        $this->data['user_id'] = $user_id;
    //        $this->data['consultant'] = $this->mu->getTablerow(T_CONSULTANTS, ['user_id' => $this->userId]);
    //            $this->data['user'] = $this->mu->getTablerow(T_USERS, ['user_id' => $this->userId]);
    //            $this->data['modules'] = $this->mu->getTableData(T_MODULES);
    //            $this->data['depts'] = $this->mu->getTableData(T_DEPTS);
    //            $this->data['selected_dept'] = $this->data['consultant'] != null ? $this->data['consultant']->department : "";
    ////            
    ////            
    //            $this->data['module1']=$this->mu->getTableRow(T_CONSULT_MODULES, ['user_id'=>$this->userId])->module1;
    ////            $this->data['module2List']=$this->mu->getSecondModules($this->userId);
    ////            $this->data['module3List']=$this->mu->getThirdModules($this->userId);
    //            $selected_modules = $this->mu->getTableRow(T_CONSULT_MODULES, ['user_id'=>$this->userId]);
    //            $this->data['module2']=$selected_modules->module2;
    //            $this->data['module3']=$selected_modules->module3;
    //            
    //            $this->processViewData('forms/consultant_info', 'Consultant Info');
    //    }

    public function saveConsultantInfo()
    {
        if ($this->input->post('submit')) {
            if ($this->isLoggedIn) {
                $user_id = intval($this->input->post('user_id'));
                $address = $this->input->post('address', true);
                $city = $this->input->post('city', true);
                $state = $this->input->post('state', true);
                $zip = $this->input->post('zip', true);
                $shift = $this->input->post('shift', true);
                $departments = $this->input->post('dept', true);
                $module1 = $this->input->post('module1');
                $module2 = $this->input->post('module2');
                $module3 = $this->input->post('module3');
                $comment = $this->input->post('comment', true);
                $phone = $this->input->post('phone', true);

                $this->mu->updateTable(T_USERS, ['phone' => $phone], ['user_id' => $user_id]);
                if ($this->mu->countTableRow(T_CONSULTANTS, ['user_id' => $user_id]) == 0) {
                    $this->mu->insertIntoTable(T_CONSULTANTS, [
                        'user_id' => $user_id,
                        'address' => $address,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $zip,
                        'shift' => $shift,
                        'department' => $departments,
                        'comment' => $comment,
                        'created_at' => CURR_DATETIME
                    ]);

                    $this->session->set_flashdata('success-msg', 'Informations saved');
                } else {
                    $this->mu->updateTable(T_CONSULTANTS, [
                        'user_id' => $user_id,
                        'address' => $address,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $zip,
                        'shift' => $shift,
                        'department' => $departments,
                        'comment' => $comment,
                        'created_at' => CURR_DATETIME
                    ], ['user_id' => $user_id]);

                    $this->session->set_flashdata('error-msg', 'Information updated');
                }

                if ($this->mu->countTableRow(T_CONSULT_MODULES, ['user_id' => $user_id]) == 0) {
                    $this->mu->insertIntoTable(T_CONSULT_MODULES, [
                        'module1' => $module1,
                        'module2' => $module2,
                        'module3' => $module3,
                        'user_id' => $user_id
                    ]);
                } else {
                    $this->mu->updateTable(T_CONSULT_MODULES, [
                        'module1' => $module1,
                        'module2' => $module2,
                        'module3' => $module3,
                        'user_id' => $user_id
                    ], ['user_id' => $user_id]);
                }
            }

            redirect('auth/dashboard');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('Auth/login');
    }

    public function dashboard()
    {
        if (!$this->isLoggedIn) {
            redirect('Auth/login');
        }
        if ($this->userType == 'consultant') {
            $this->data['user_id'] = $this->userId;
            $this->data['consultant'] = $this->mu->getTablerow(T_CONSULTANTS, ['user_id' => $this->userId]);
            $this->data['user'] = $this->mu->getTablerow(T_USERS, ['user_id' => $this->userId]);
            $this->data['modules'] = $this->mu->getTableData(T_MODULES);
            $this->data['states'] = $this->mu->getTableData('states');
            $this->data['depts'] = $this->mu->getTableData(T_DEPTS);
            $this->data['selected_dept'] = $this->data['consultant'] != null ? $this->data['consultant']->department : "";
            //            
            //            
            $selected_modules = $this->mu->getTableRow(T_CONSULT_MODULES, ['user_id' => $this->userId]);
            if ($selected_modules != null) {
                $this->data['module1'] = $selected_modules->module1;
                $this->data['module2List'] = $this->mu->getSecondModules($this->userId);
                $this->data['module3List'] = $this->mu->getThirdModules($this->userId);

                $this->data['module2'] = $selected_modules->module2;
                $this->data['module3'] = $selected_modules->module3;
            } else {
                $this->data['module1'] = "";
                //            $this->data['module2List']=$this->mu->getSecondModules($this->userId);
                //            $this->data['module3List']=$this->mu->getThirdModules($this->userId);

                $this->data['module2'] = "";
                $this->data['module3'] = "";
            }


            $this->processViewData('forms/consultant_info', 'Consultant Info');
        } else {
            $this->data['facilities'] = $this->mu->getTableData(T_FACILITIES);
            $assigned_su = $this->db->select('s.event_id,count(su.event_id) as count_su,s.facility_id,s.number_su,s.shift,l.name as location')
                ->from(T_SHIFTS . ' AS s')
                ->join(T_A_SUPER_USERS . ' AS su', 's.event_id=su.event_id', 'LEFT')
                ->join(T_LOCATIONS . ' AS l', 'l.location_id=s.location_id', 'LEFT')
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
            $this->data['dataPoints']= json_encode($dataPoints);
            $this->processViewData('admin/dashboard', 'Admin Dashboard');
        }
    }
}
