<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
	}

	public function index()
	{
		if ($this->session->userdata('is_authorized')) {
			redirect('dashboard');
		} else {
			redirect('auth/login');
		}
	}

	public function login()
	{
		if ($this->session->userdata('is_authorized')) {
			redirect('dashboard');
		}

		$params = [
			'title' => constant('APP_NAME') . ' - Iniciar sesión',
			'styles' => [
				base_url('public/css/auth.css')
			]
		];

		$this->load->view('header', $params);
		$this->load->view('partials/login/container');
		$this->load->view('footer');
	}

	public function verify()
	{
		if ($this->session->userdata('is_authorized')) {
			redirect('dashboard');
		}

		$this->Auth_model->login([
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password')
		]);
	}

	public function logout()
	{
		if ($this->session->userdata('is_authorized')) {
			$this->session->sess_destroy();
			echo site_url();
		}
	}
}
