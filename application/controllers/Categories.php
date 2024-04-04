<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Categories extends CI_Controller {

		/**
		* [__construct description]
		*/
		public function __construct(){
			parent::__construct(); 

			$this->load->model('Movie_model');
			$this->load->model('Productor_model');
			$this->load->model('Gender_model');
			$this->load->model('Category_model');
			$this->load->model('User_model');
			$this->load->model('Status_model');

			$this->load->library('pagination');
		}

		/**
		* [index description]
		* @return [type] [description]
		*/
		public function index(){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$params = array(
					'title' => constant('APP_NAME') . ' | Categorías',
					'styles' => array(
						base_url('public/css/libs/dataTables.bootstrap.min.css'),
						base_url('public/css/libs/buttons.bootstrap.min.css'),
						base_url('public/css/dashboard.css')
					),
					'scripts' => array(
						base_url('public/js/libs/jszip.min.js'),
						base_url('public/js/libs/pdfmake.min.js'),
						base_url('public/js/libs/vfs_fonts.js'),
						base_url('public/js/libs/dataTables.min.js'),
						base_url('public/js/libs/dataTables.bootstrap.min.js'),
						base_url('public/js/libs/dataTables.buttons.min.js'),
						base_url('public/js/libs/buttons.bootstrap.min.js'),
						base_url('public/js/libs/buttons.html5.min.js'),
						base_url('public/js/categories.js')
					),
					'get_all_categories' => $this->Category_model->get_all_categories(),
					'user_avatar' => $this->User_model->has_user_avatar($this->session->userdata('id_user'))
				);
				$this->load->view('header', $params);
				$this->load->view('layouts/dashboard/navbar');
				$this->load->view('layouts/dashboard/sidebar');
				$this->load->view('partials/categories/container');
				$this->load->view('layouts/dashboard/footer');
				$this->load->view('footer');
			}		
		}

		/**
		* [add description]
		*/
		public function add(){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$params = array(
					'title' => constant('APP_NAME') . ' | Categorías',
					'styles' => array(base_url('public/css/dashboard.css')),
					'scripts' => array(base_url('public/js/categories.js')),
					'get_all_status' => $this->Status_model->get_all_status(),
					'user_avatar' => $this->User_model->has_user_avatar($this->session->userdata('id_user'))
				);
				$this->load->view('header', $params);
				$this->load->view('layouts/dashboard/navbar');
				$this->load->view('layouts/dashboard/sidebar');
				$this->load->view('partials/categories/add');
				$this->load->view('layouts/dashboard/footer');
				$this->load->view('footer');
			}		
		}

		/**
		* [insert description]
		* @return [type] [description]
		*/
		public function insert(){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$insert = array(
					'category_name' => trim($this->input->post('category_name_insert')), 
					'category_slug' => trim($this->input->post('category_slug_insert')), 
					'category_status' => trim($this->input->post('category_status_insert'))
				);
				$this->Category_model->insert_model($insert);
			}
		}

		/**
		* [view description]
		* @param  [type] $id_category [description]
		* @return [type]              [description]
		*/
		public function view($id_category){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$params = array(
					'title' => constant('APP_NAME') . ' | Categorías',
					'styles' => array(base_url('public/css/dashboard.css')),
					'scripts' => array(base_url('public/js/categories.js')),
					'view_category' => $this->Category_model->get_category_by('id_category', $id_category),
					'user_avatar' => $this->User_model->has_user_avatar($this->session->userdata('id_user'))
				);
				$this->load->view('header', $params);
				$this->load->view('layouts/dashboard/navbar');
				$this->load->view('layouts/dashboard/sidebar');
				$this->load->view('partials/categories/view');
				$this->load->view('layouts/dashboard/footer');
				$this->load->view('footer');
			}		
		}

		/**
		* [filter_by description]
		* @param  [type] $id_category [description]
		* @return [type]              [description]
		*/
		public function filter_by($id_category){
			$total_rows = 0;
			if ($this->Movie_model->get_count_movies_by_category($id_category) != FALSE) {
				$total_rows = $this->Movie_model->get_count_movies_by_category($id_category)->num_rows();
			}else{
				$total_rows = 0;
			}

			$config = array();
	       	$config['base_url'] = base_url() . 'categories/filter_by/' . $id_category . '/';
	       	$config['total_rows'] = $total_rows;
	       	$config['per_page'] = 4; 
    		$config['uri_segment'] = 4;
    		
			// $config['num_links'] = round(($this->Movie_model->get_all_movies_activated()->num_rows() / 8));
			// $config['use_page_numbers'] = TRUE;

	       	$config['full_tag_open']  = '<nav aria-label="Page navigation"><ul class="pagination">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			$config['first_link'] = '&laquo; Primera';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			$config['last_link'] = 'Última &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = 'Siguiente <span class="glyphicon glyphicon-chevron-right"></span>';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '<span class="glyphicon glyphicon-chevron-left"></span> Anterior';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';

	       	$this->pagination->initialize($config);
	       	
	       	$results_paginated = $this->Category_model->get_movies_by_category($config['per_page'], $this->uri->segment(4), 'cm_cat_mov.id_category', decryp($id_category));
	       	$links_created = $this->pagination->create_links();

			$params = array(
				'title' => constant('APP_NAME') . ' - Búsqueda por categoría',
				'styles' => array(
					base_url('public/css/libs/owl.carousel.css'),
					base_url('public/css/libs/owl.theme.css'),
					base_url('public/css/libs/owl.transitions.css'),
					base_url('public/css/welcome.css')
				),
				'scripts' => array(
					base_url('public/js/libs/owl.carousel.min.js'),
					base_url('public/js/welcome.js')
				),
				'view_category' => $this->Category_model->get_category_by('id_category', $id_category),
				'get_movies_most_viewed' => $this->Movie_model->get_movies_most_viewed(8),
				'get_new_movies' => $this->Movie_model->get_new_movies(8),		
				'get_all_productors_activated' => $this->Productor_model->get_all_productors_activated(),	
				'get_all_genders_activated' => $this->Gender_model->get_all_genders_activated(),	
				'get_all_categories_activated' => $this->Category_model->get_all_categories_activated(),	
				'results_paginated' => $results_paginated,
				'links_created'=> $links_created,				
				'user_avatar' => $this->User_model->has_user_avatar($this->session->userdata('id_user'))
			);
			$this->load->view('header', $params);				
			$this->load->view('layouts/welcome/navbar');				
			$this->load->view('layouts/welcome/carousel-news');				
			$this->load->view('layouts/welcome/carousel-views');				
			$this->load->view('partials/welcome/filter_by_categories');				
			$this->load->view('layouts/welcome/footer');				
			$this->load->view('footer');
		}

		/**
		* [edit description]
		* @param  [type] $id_category [description]
		* @return [type]              [description]
		*/
		public function edit($id_category){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$params = array(
					'title' => constant('APP_NAME') . ' | Categorías',
					'styles' => array(base_url('public/css/dashboard.css')),
					'scripts' => array(base_url('public/js/categories.js')),
					'id_category_encryp' => $id_category,
					'edit_category' => $this->Category_model->get_category_by('id_category', $id_category),
					'get_all_status' => $this->Status_model->get_all_status(),
					'user_avatar' => $this->User_model->has_user_avatar($this->session->userdata('id_user'))
				);
				$this->load->view('header', $params);
				$this->load->view('layouts/dashboard/navbar');
				$this->load->view('layouts/dashboard/sidebar');
				$this->load->view('partials/categories/edit');
				$this->load->view('layouts/dashboard/footer');
				$this->load->view('footer');
			}		
		}

		/**
		* [update description]
		* @return [type] [description]
		*/
		public function update(){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$update = array(
					'id_category' => trim($this->input->post('id_category_update')), 
					'category_name' => trim($this->input->post('category_name_update')), 
					'category_slug' => trim($this->input->post('category_slug_update')), 
					'category_status' => trim($this->input->post('category_status_update'))
				);
				$this->Category_model->update_model($update);
			}			
		}

		/**
		* [delete description]
		* @return [type] [description]
		*/
		public function delete(){
			if (!$this->session->userdata('is_admin_logged_in') && 
				!$this->session->userdata('is_guest_logged_in')) {
				redirect(site_url());
			} else {
				$id_category = trim($this->input->post('id_category_delete'));
				
				$this->Category_model->delete_model($id_category);
			}			
		}
	}
?>