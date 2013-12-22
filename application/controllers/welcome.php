<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    private $data = [];

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Cookie');

        // 默认情况下显示logo
        $this->data['header'] = false;
    }

	public function index() {
        $this->data['title'] = "哈尔滨华凯电能科技有限公司";
		$this->load->view('common/header', $this->data);
		$this->load->view('index');
		$this->load->view('common/footer');
	}

    public function login() {
        $token  = $this->input->cookie('token', true);
        $un = $this->input->cookie('username', true);

        if ($token && $un) {
            $result = $this->Cookie->query_cookie_state($un, $token);

            if ($result) {
                redirect('/admin', 'refresh');
            }
        }

        $this->data['title'] = "管理员登录界面";
		$this->load->view('common/header', $this->data);
		$this->load->view('admin/login');
		$this->load->view('common/footer');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
