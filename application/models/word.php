<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Инициализация структуры базы
 */
class Word extends CI_Model {

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

}
