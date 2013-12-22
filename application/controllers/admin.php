<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author luofei
 * @email luofeihit2010@gmail.com
 * @time 2013-12-21
 *
 * */

class Admin extends CI_Controller {

    private $data = [];
    // 当前用户
    private $un = "";

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Cookie');
        $this->load->model('News');

        // 去掉header上的logo
        $this->data['header'] = true;

        /*
         * admin 的所有功能都需要做身份验证，一旦发现不正确就跳转页面
         * */
        $token  = $this->input->cookie('token', true);
        $un = $this->input->cookie('username', true);

        if ($token && $un) {
            $result = $this->Cookie->query_cookie_state($un, $token);

            if (!$result) {
                redirect('/welcome/login', 'refresh');
            }
        } else {
            redirect('/welcome/login', 'refresh');
        }
    }

    // TODO 增加管理员后台界面
	public function index() {
        $this->data['title'] = "管理员后台界面";
		$this->load->view('common/header', $this->data);
		$this->load->view('index');
		$this->load->view('common/footer');
	}

    /*
     * 管理员信息发布平台
     * */
    public function publish() {
        $this->data['title'] = "新闻发布平台";
        $msg = [];
        $msg['category'] = $this->News->get_category();

        $this->load->view('common/header', $this->data);
        $this->load->view('admin/publish', $msg);
        $this->load->view('common/footer');
    }

    /*
     * 管理员退出功能
     * */
    public function logout() {
        $this->Cookie->destroy_cookie();
        redirect('/welcome/login', 'refresh');
    }

    /*
     * 保存新闻功能
     * */
    public function save_news() {
        $this->un = $this->input->cookie('username', true);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $config = [
            [
                'field' => 'title',
                'label' => '新闻标题',
                'rules' => 'required'
            ],
            [
                'field' => 'category',
                'label' => '新闻类别',
                'rules' => 'required'
            ],
            [
                'field' => 'content',
                'label' => '新闻内容',
                'rules' => 'required|min_length[10]'
            ]
        ];

        // 绑定验证规则
        $this->form_validation->set_rules($config);
        // 数据不合法
        if (!$this->form_validation->run()) {
            echo "false";
        } else {
            $msg = [
                'id' => '',
                'title' => $this->input->post('title', true),
                'content' => $this->input->post('content', true),
                'category' => $this->input->post('category', true),
                'writer' => $this->un,
                'pub_time' => date('Y-m-d G:i:s'),
                'c_time' => '',
                'show' => 1,
                'readers' => 0
            ];
            if ($this->News->save_news($msg)) 
                echo "true";
            else 
                echo "false";
        }
    }
}
