<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Инициализация структуры базы
 */
class Word extends CI_Model {

	/**
	 * Поиск слова
	 * @param int
	 * @return array
	 */
	public function getWord($word_id) {
		$this->db->select("w.*, l.language")
			->from('word AS w')
			->join('language AS l', 'l.language_id = w.language_id');
		$this->db->where('w.language_id', 1);

		$this->db->where('w.word_id', (int)$word_id);

		$this->db->group_by('w.word_id');

		$result = $this->db->get();
		$result = $result->row_array();

		return $result;
	}

	public function getWords($params = array()) {
		if (!isset($params['limit'])) {
			$limit = 30;
		} else {
			$limit = $params['limit'];
		}

		$this->db->select("w.*, l.language")
			->from('word AS w')
			->join('language AS l', 'l.language_id = w.language_id');

		$this->db->where('w.language_id', 1);

		if (!empty($params['word'])) {
			$this->db->like('w.word', $params['word']);
		}

		$this->db->group_by('w.word_id');

		if (isset($params['page']) && $params['page'] >= 0) {
			$offset = $limit*(int)$params['page'] - $limit;
			$this->db->limit($limit, $offset);
		} else {
			$this->db->limit($limit);
		}

		$order_array = array(
			'word_id',
			'word',
		);

		if (isset($params['order']) &&
			in_array($params['order'], $order_array)
		) {
			$order = $params['order'];
		} else {
			$order = 'w.word_id';
		}

		$by_array = array('asc', 'desc');
		if (isset($params['by']) &&
			in_array($params['by'], $by_array)
		) {
			$by = $params['by'];
		} else {
			$by = 'asc';
		}

		$this->db->order_by($order, $by);

		$result = $this->db->get();
		$result = $result->result_array();

		return $result;
	}

	public function getWordsTotal($params = array()) {
		$this->db->select("COUNT(DISTINCT(w.word_id)) AS total")
			->from('word AS w')
			->join('language AS l', 'l.language_id = w.language_id');

		$this->db->where('w.language_id', 1);

		if (!empty($params['word'])) {
			$this->db->like('w.word', $params['word']);
		}

		$result = $this->db->get();
		$result = $result->row_array();

		return $result['total'];
	}

	/**
	 * Поиск переводов слова
	 * @param mixed(int | string) $word_id Идентфикатор слова
	 * @param int $language_id_to Идентификатор языка перевода
	 */
	public function getTranslateWords($word_id, $language_id_to = 2) {
		$word = $this->getWord($word_id);

		$this->db->select("w.*, l.language")
			->from("word AS w")
			->join('word_to_word AS wtw', 'wtw.word_id_to = w.word_id')
			->join('language AS l', 'l.language_id = w.language_id')
			->where('wtw.word_id_from', (int)$word_id);

		$this->db->group_by('w.word_id');

		$this->db->order_by('w.word_id', 'asc');

		$result = $this->db->get();
		$result = $result->result_array();

		if (!$result) {
			$result = $this->translateapi->getTranslateWords($word_id);
			if ($result) {
				return $this->getTranslateWords($word_id);
			} else {
				return false;
			}
		}

		return array(
			'word' => $word['word'],
			'word_id' => $word['word_id'],
			'translate_words' => $result,
		);
	}

	/**
	 * Сохранение переводов
	 * @param int Идентификатор слова
	 * @param array Список переводов
	 * @param int Идентификатор языка переводов
	 */
	public function saveTranslateWords($word_id_from, $words = array(), $language_id_to = 2) {
		$words_id_to = array();
		$data_ar = array();
		foreach($words as $w) {
			$data = array(
				'word' => trim($w),
				'language_id' => 2,
			);

			$this->db->insert('word', $data);
			$data_ar[] = array(
				'word_id_from' => $word_id_from,
				'word_id_to' => $this->db->insert_id(),

			);
		}

		$this->db->insert_batch('word_to_word', $data_ar);
	}

}
