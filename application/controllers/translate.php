<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->url = 'translate';
	}

	public function index() {
		if (!$this->db_install->check()) {
			$this->session->set_flashdata('error', 'Таблиц в базе нет');
			redirect($this->url.'/db');
		}

		$content = $this->load->view('translate', null, false);
		$this->load->view('layout', array(
			'content' => $content,
			'title' => 'Перевод',
			'error' => $this->session->flashdata('error'),
			'success' => $this->session->flashdata('success'),
		));
	}

	public function db() {
		if ($this->db_install->check()) {
			$this->session->set_flashdata('success', 'Все таблицы созданы');
			redirect($this->url);
		}

		if ($this->input->post('Form')) {
			$this->db_install->run();
			$this->session->set_flashdata('success', 'Таблицы успешно созданы');
			redirect($this->url);
		}

		$content = $this->load->view('db_install', null, true);
		$this->load->view('layout', array(
			'content' => $content,
			'title' => 'Инициализация базы',
			'error' => $this->session->flashdata('error'),
			'success' => $this->session->flashdata('success'),
		));
	}
}
