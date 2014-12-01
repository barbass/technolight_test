<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Добавляем поля сотрудникам услугодателей
 */
class Migration_init_tables extends CI_Migration {
	public function up() {
		// список языков
		$fields_language = array(
			'language_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
			),
			'language' => array(
				'type' => 'VARCHAR',
				'constraint' => 45,
			),
		);

		$this->dbforge->add_field($fields_language);
		$this->dbforge->add_key('language_id', true);
		$this->dbforge->create_table('language');
		$this->db->query('INSERT INTO `language` (language_id, language) VALUES("1", "english"), ("2", "русский") ');

		// список слов
		$fields_word = array(
			'word_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
			),
			'word' => array(
				'type' => 'VARCHAR',
				'constraint' => 150,
			),
			'language_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			),
		);

		$this->dbforge->add_field($fields_word);
		$this->dbforge->add_key('word_id', true);
		$this->dbforge->add_key('language_id');
		$this->dbforge->create_table('word');
		$this->db->query('ALTER TABLE `word` ADD FOREIGN KEY ( `language_id` ) REFERENCES `language` (`language_id`) ON DELETE RESTRICT ON UPDATE RESTRICT');

		// связи слов
		$fields_word_to_word = array(
			'word_id_from' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			),
			'word_id_to' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			),
		);

		$this->dbforge->add_field($fields_word_to_word);
		$this->dbforge->add_key('word_id_from');
		$this->dbforge->add_key('word_id_to');
		$this->dbforge->create_table('word_to_word');
		$this->db->query('ALTER TABLE `word_to_word` ADD FOREIGN KEY ( `word_id_from` ) REFERENCES `word` (`word_id`) ON DELETE CASCADE ON UPDATE RESTRICT');
		$this->db->query('ALTER TABLE `word_to_word` ADD FOREIGN KEY ( `word_id_to` ) REFERENCES `word` (`word_id`) ON DELETE CASCADE ON UPDATE RESTRICT');
	}

	public function down() {
		$this->dbforge->drop_table('language');
		$this->dbforge->drop_table('word');
		$this->dbforge->drop_table('word_to_word');
	}

}
