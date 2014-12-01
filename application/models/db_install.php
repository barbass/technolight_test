<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Инициализация структуры базы
 */
class Db_install extends CI_Model {

	/**
	 * Проверка структуры базы
	 * @return bool
	 */
	public function check() {
		$tables = $this->db->list_tables();
		if (count($tables) >= 3) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Запуск создания таблиц
	 */
	public function run() {
		if (!$this->check()) {
			$this->load->library('migration');
			$this->migration->current();
			$this->parseWord();
		}
	}

	/**
	 * Парсинг словаря и запись в базу
	 */
	private function parseWord() {
		$file = fopen(BASEPATH.'../'.'resource/words.txt', 'r');

		$data_query = array();
		$i = 0;
		while (($str = fgets($file)) !== false) {
			$data_query[] = array(
				'language_id' => 1,
				'word' => trim($str),
			);
		}

		$this->db->insert_batch('word', $data_query);
	}


}
