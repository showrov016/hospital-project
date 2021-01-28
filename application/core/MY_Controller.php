<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of MY_Controller
 * Base class for all custom controller of the project
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 * @property CI_Benchmark $benchmark
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Exceptions $exceptions
 * @property CI_Form_validation $form_validation
 * @property CI_Hooks $hooks
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_Model $model
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Profiler $profiler
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_Table $table
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $user_agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Xmlrpcs $xmlrpcs
 * @property Model_Users $mu
 */
class MY_Controller extends CI_Controller {

    /**
     * Detect Ajax request
     * @var bool
     */
    protected $isAjaxRequest;

    /**
     * Detect if admin is logged in or not
     * @var bool
     */
    protected $isLoggedIn = false;

    /**
     * Hold array of data to pass in CI view
     *
     * @var array
     */
    protected $data;

    /**
     * Hold array of Logged in user information
     *
     * @var array
     */
    protected $userData;

    /**
     * For different level of code execution
     *
     * @var bool Error flag
     */
    protected $errorFlag;

    /**
     * For returning error message
     *
     * @var bool Error text message
     */
    protected $errorText;

    /**
     * Get request headers
     *
     * @var array Request headers key/value pair
     */
    protected $headers;
    protected $userType;
    protected $userId;
    protected $activeMenuSlug;

    function __construct() {
        parent::__construct();

        // Get request headers
        $this->headers = $this->input->request_headers();


        // Check ajax request
        $this->isAjaxRequest = $this->input->is_ajax_request();

        $this->errorFlag = false;
        $this->errorText = '';
        $this->activeMenuSlug = '';
        $this->userData = [];
        $this->userId = null;

        // Load user model
        $this->load->model('Model_Users', 'mu');

        // Check user session is active or not
        if ($this->session->has_userdata('userId')) {

            $this->isLoggedIn = true;

            $this->userType = $this->session->has_userdata('type') ? strtolower($this->session->userdata('type')) : '';

            $this->userData = [
              'user_id' => $this->session->userdata('userId'),
              'username' => $this->session->userdata('username'),
              'fname' => $this->session->userdata('first_name'),
              'lname' => $this->session->userdata('last_name'),
              'type' => $this->userType
            ];

            

            $this->userId = $this->userData['user_id'];

            
        } else {
            $this->isLoggedIn = false;
        }

        $this->data['viewClass'] = true;
    }

    /**
     * Print json output
     *
     * @param array $data
     */
    protected function jsonOutput($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * This function Initialize all basic parameters
     * which are to be passed in view page
     *
     * @param string $viewPage Page name inside codeIgniter view template
     * @param string $pageTitle Page title for the web page in the browser
     * @param string $activeMenu Status of menu which is currently active
     */
    protected function processViewData(string $viewPage, string $pageTitle = null, string $activeMenu = null, bool $exit = false) {

        $this->prepareViewData($viewPage, $pageTitle, $activeMenu);

        $this->load->view(THEMES_TEMPLATE, $this->data);

        if ($exit) {
            exit();
        }
    }

    /**
     * Prepare data variable which will be passed to view page
     * 
     * @param string $viewPage
     * @param string $pageTitle
     * @param string $activeMenu
     */
    protected function prepareViewData($viewPage, $pageTitle, $activeMenu = '') {
        $this->data['mainView'] = $viewPage;
        $this->data['pageTitle'] = $pageTitle;
        $this->data['activeMenu'] = empty($activeMenu) ? $this->activeMenuSlug  : $activeMenu;
        $this->data['isLoggedIn'] = $this->isLoggedIn;
        $this->data['userData'] = $this->userData;
        if (!isset($this->data['usertype']) || empty($this->data['usertype'])) {
            $this->data['usertype'] = $this->userType;
        }
        $this->data['csrf'] = [
          'name' => $this->security->get_csrf_token_name(),
          'hash' => $this->security->get_csrf_hash()
        ];
    }

    /**
     * Image upload process
     *
     * @param string $fileField Form file input field name
     * @param sring $uploadPath File upload path string
     * @param sring $type File category
     * @param sring $old_file File to be deleted
     *
     * @return array Result of image upload process
     */
    protected function processFileUpload($fileField, $uploadPath, $type = '', $old_file = '') {
        $result = ['success' => false, 'msg' => ''];

        /**
         * Check if upload path is working or not
         */
        if (!file_exists($uploadPath) && !is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0775, true)) {
                $result['msg'] = 'Error! Permission denied in upload path!';
            }
        } elseif (!is_writable($uploadPath)) {
            $result['msg'] = 'Error! Upload path is not writable, permission denied!';
        } else {

            $imageName = '';
            if (file_exists($_FILES[$fileField]['tmp_name']) || is_uploaded_file($_FILES[$fileField]['tmp_name'])) {
                $fileName = '';

                $info = getimagesize($_FILES[$fileField]['tmp_name']);

                if ($info === FALSE) {
//                    $config['max_size'] = 25600;
//                    $fileName = 'file' . time() . $_FILES[$fileField]['name'];
//                    $config['allowed_types'] = '*';
                    $result['msg'] = 'Error! Not an image!';
                    goto jumpError;
                } else {
                    $config['allowed_types'] = 'jpg|png|jpeg|JPG|PNG|JPEG';
                    $config['max_size'] = 5120;
                    $config['min_width'] = 32;
                    $config['min_height'] = 32;
                    $fileName = $type . $this->userId . '_' . time() . image_type_to_extension($info[2]);
                }

                /**
                 * Process file upload
                 */
                $config['overwrite'] = true;
                $config['file_name'] = $fileName;
                $config['upload_path'] = $uploadPath;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($fileField)) {
                    $result['msg'] = 'Error! ' . $this->upload->display_errors();
                } else {
                    if (!empty($old_file)) {
// delete old image
                        unlink($uploadPath . $old_file);
                    }

                    $result = [
                      'success' => true,
                      'msg' => 'File uploaded successfully',
                      'name' => $fileName,
                      'image' => $info ? true : false
                    ];
                }
            } else {
                $result['msg'] = 'Error! Uploaded file not found!';
            }
        }
        jumpError:

        return $result;
    }

    /**
     * Sanitize the post values with custom / CI logics
     *
     * @param array $requests Array of post requests to be searched
     *              $request[0] = Post Value | $request[1] = Value Type
     * @return array
     */
    public function getPostValues(array $requests = []): array {
        $response = [];

        if (!empty($requests)) {
            foreach ($requests as $val) {

                $type = !isset($val[1]) || empty($val[1]) ? '' : $val[1];

                switch ($type) {
                    case 'int':
                        $response[] = isset($_POST[$val[0]]) ? intval($_POST[$val[0]]) : null;
                        break;
                    case 'string':
                        $response[] = isset($_POST[$val[0]]) ? trim($this->input->post($val[0], true)) : null;
                        break;
                    case 'float':
                        $response[] = isset($_POST[$val[0]]) ? floatval($_POST[$val[0]]) : null;
                        break;
                    case 'bool':
                        $response[] = isset($_POST[$val[0]]) && intval($_POST[$val[0]]) > 0 ? true : false;
                        break;
                    default:
                        $response[] = $this->security->xss_clean($_POST[$val[0]]);
                        break;
                }
            }
        }

        return $response;
    }

    /*
     * Before redirecting to dashboard get required parameter values
     * 
     * @param string $type
     */

    public function getUserDashboardParameters($type = null) {
        $this->load->model('Model_Teacher','mt');
        $this->load->model('Model_Users','mu');
        if ($type != null && $this->isLoggedIn) {
            if ($type == 'teacher') {
                $this->data['room_id'] = $this->mt->getTableRow(T_CLASSROOM, array('teacher_id' => $this->userData['teacher_id']))->classroom_id;
                $this->data['classusers'] = $this->mu->getTableData('class_auth', ['class_id' => $this->data['room_id']], 'auth_name,auth_email', 0, 0, [], false, true);
                $this->data['propic_link'] = $this->mt->getTableRow(T_USER, array('user_id' => $this->userData['user_id'], 'type' => $this->userType));
                $this->session->set_userdata('classid', $this->data['room_id']); //classroom join invitation purpose
                $this->session->set_userdata('activebtn', 'tuition');
            }
        }else{
            redirect('login');
        }
    }

}


