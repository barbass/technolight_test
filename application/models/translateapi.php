<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Работа с api сервисами
 */
class TranslateApi extends CI_Model {

	/**
	 * Поиск перевода слова
	 * @param int $word_id Идентификатор слова
	 * @param int $language_id_to Идентификатор языка перевода
	 * @return mixed (array | bool)
	 */
	public function getTranslateWords($word_id, $language_id_to = 2, $api = 'yandex') {
		$word = $this->word->getWord($word_id);

		if ($api == 'google') {
			$words = $this->translateGoogle($word['word']);
		} elseif($api == 'yandex') {
			$words = $this->translateYandex($word['word']);
		} else {
			return false;
		}

		if ($words) {
			// если не смог перевести и вернул тоже слово
			foreach($words as $w) {
				if ($w == $word['word']) {
					return false;
				}
			}

			$this->word->saveTranslateWords($word_id, $words, $language_id_to);

			return array(
				'word' => $word['word'],
				'word_id' => $word['word_id'],
				'translate_words' => $words,
			);
		}

		return false;
	}

	/**
	 * Работа с api Google
	 * @param string Искомая фраза
	 * @param string Язык с
	 * @param string Язык искомого перевода
	 * @return array
	 */
	private function translateGoogle($word = '', $source = 'en', $target = 'ru') {
		$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyBvB2tU4zJTE6LUKhcqQivm5B1fu0audcA&source=en&target=ru&q=".$word;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * Работа с api Google
	 * @param string Искомая фраза
	 * @param string Язык с
	 * @param string Язык искомого перевода
	 * @return array
	 */
	private function translateYandex($word = '', $source = 'en', $target = 'ru') {
		$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20141201T123509Z.dd98d226c6e6ba5a.0335b8cbfe1aab3bd3725bb8207ca8cb4f1a1d3a&lang=".$source."-".$target."&text=".$word;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);
		$response = json_decode($result, true);

		curl_close($ch);

		if (isset($response['text'])) {
			return $response['text'];
		}
	}

}
