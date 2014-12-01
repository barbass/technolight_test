<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller {
	public function __construct() {
		parent::__construct();

		if (!$this->input->is_ajax_request() && !$this->input->is_cli_request()) {
			$this->output->enable_profiler(TRUE);
		}
		$this->url = 'translate/';
	}

	/**
	 * Основная страница
	 */
	public function index() {
		if (!$this->db_install->check()) {
			$this->session->set_flashdata('error', 'Таблиц в базе нет');
			redirect($this->url.'/db');
		}

		$form = $this->input->get();
		$url = '?page={page}';
		if (!empty($form['word'])) {
			$url = '?page={page}&word='.$form['word'];
		}
		$url_form = $this->url.'index/?';
		if (!empty($form['word'])) {
			$url_form .= 'word='.$form['word'];
		}
		if (!empty($form['page'])) {
			$url_form .= 'page='.$form['page'];
		}

		$words = $this->word->getWords($form);
		$words_total = $this->word->getWordsTotal($form);

		$this->pagination_library->total = $this->word->getWordsTotal($form);
		$this->pagination_library->page = (isset($form['page'])) ? $form['page'] : 1;
		$this->pagination_library->limit = 30;
		$this->pagination_library->url = base_url().$this->url.'index/'.$url;
		$pages = $this->pagination_library->render();

		$content = $this->load->view('translate', array(
			'words' => $words,
			'pages' => $pages,
			'form' => $form,
			'action' => array(
				'form' => $url_form,
				'get_translate' => base_url($this->url.'ajax_translate'),
			),
		), true);
		$this->load->view('layout', array(
			'content' => $content,
			'title' => 'Перевод',
			'error' => $this->session->flashdata('error'),
			'success' => $this->session->flashdata('success'),
		));
	}

	/**
	 * Обновление структуры базы
	 */
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

	/**
	 * Поиск перевода
	 */
	public function ajax_translate() {
		$word_id = $this->input->post('word_id');
		$data = $this->word->getTranslateWords($word_id);

		$result = array(
			'success' => true,
			'translate_words' => array(),
		);
		if ($data) {
			$result['word_id'] = $data['word_id'];
			$result['word'] = $data['word'];
			$result['translate_words'] = $data['translate_words'];
		} else {
			$result['success'] = false;
		}

		echo json_encode($result);
	}
}
