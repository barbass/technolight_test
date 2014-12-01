	<table class='table'>
		<thead>
			<tr>
				<th>Номер</th>
				<th>Слово</th>
				<th>Язык</th>
				<th>Действие</th>
				<th>Переводы</th>
			</tr>
			<tr>
				<th></th>
				<th>
					<input type='text' class='form-control input-sm' name='word' value='<?php echo (!empty($form['word'])) ? $form['word']: '';?>' >
				</th>
				<th>
					<a href='javascript:void(0);' class='search'>Поиск</a>
					/
					<a href='javascript:void(0);' class='clear-search'>Сброс</a>
				</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<?php if (!empty($words)) { ?>
			<?php foreach($words as $w) { ?>
				<tr>
					<td><?php echo $w['word_id'];?></td>
					<td><?php echo $w['word'];?></td>
					<td><?php echo $w['language'];?></td>
					<td>
						<a data-word_id='<?php echo $w['word_id'];?>' class='get_translate' href='javascript:void(0);'>Получить перевод</a>
					</td>
					<td class='translate'></td>
				</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td>Слов нет</td>
			</tr>
		<?php } ?>
	</table>

<ul class='pagination hide'></ul>

<script type='text/javascript'>
	$(document).ready(function() {
		getWords();

		// Получение перевода
		$('.table').on('click', '.get_translate',function() {
			var word_id = $(this).attr('data-word_id');
			getTranslate(word_id);
		});

		// Поиск
		$('.search').on('click', function(event) {
			event.preventDefault();
			getWords();
		});

		// Сброс поиска
		$('.clear-search').on('click', function(event) {
			event.preventDefault();
			$('.table input[name]').val('');
			getWords();
		});

		$('.pagination').on('click', 'a', function(event) {
			event.preventDefault();
			var page = $(this).attr('data-page');
			getWords({
				'page': page,
			});
		});
	});

	/**
	 * Получение списка переводов
	 * @param int
	 */
	function getTranslate(word_id) {
		$('.alert').remove();

		$.ajax({
			url: "<?php echo $action['get_translate'];?>",
			type: "POST",
			dataType: 'json',
			data: {'word_id':word_id},
			success: function(json) {
				if (!json) {
					$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Ошибка обработки ответа</strong></div>");
				} else if(json['success'] === true) {
					$('.container-fluid .row').before("<div class='alert alert-dismissable alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Пееревод найден</strong></div>");

					insertTranslate(word_id, json['translate_words']);
				} else if(json['success'] === false) {
					$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Перевод не обнаружен</strong></div>");
				}
			},
			error: function() {
				$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Ошибка на сервере</strong></div>");
			},
		});
	}

	/**
	 * Вставка перевода слов
	  * @param int
	  * @param object
	 */
	function insertTranslate(word_id, words) {
		var block = $('.table').find('a[data-word_id = '+word_id+']').parents('tr').eq(0).find('td.translate');
		$(block).html('');
		var html_ar = ["<table class='table'><tr><th>Слово</th><th>Язык</th></tr>"];
		var str = "";
		for(var key in words) {
			str = "<tr>";
			str += "<td>"+words[key]['word']+"</td>";
			str += "<td>"+words[key]['language']+"</td>";
			str += "</tr>";
			html_ar.push(str);
		}
		html_ar.push("</table>");

		$(block).append(html_ar.join(''));
	}

	/**
	 * Подгрузка новых слов
	 *
	 */
	function getWords(params) {
		$('.alert').remove();

		var data = {};
		var word =  $('.table input[name=word]').val();
		if (word.length != 0) {
			data['word'] = word;
		}
		if (params && params['page']) {
			data['page'] = params['page'];
		}

		$.ajax({
			url: "<?php echo $action['get_words'];?>",
			type: "POST",
			dataType: 'json',
			data: data,
			success: function(json) {
				if (!json) {
					$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Ошибка обработки ответа</strong></div>");
				} else if(json['success'] === true) {
					insertWords(json);
				} else if(json['success'] === false) {
					$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Слова не обнаружены</strong></div>");
				}
			},
			error: function() {
				$('.container-fluid .row').before("<div class='alert alert-dismissable alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>Ошибка на сервере</strong></div>");
			},
		});
	}

	/**
	 * Вставка слов, изменение пагинации
	 * @param object
	 */
	function insertWords(data) {
		if (data['words'] && data['words'].length > 0) {
			var html_ar = [];
			for(var key in data['words']) {
				var word = data['words'][key];

				var html = "<tr>";

				html += "<td>"+word['word_id']+"</td>";
				html += "<td>"+word['word']+"</td>";
				html += "<td>"+word['language']+"</td>";

				html += "<td>";
				html += "<a data-word_id='"+word['word_id']+"' class='get_translate' href='javascript:void(0);'>Получить перевод</a>";
				html += "</td>";
				html += "<td class='translate'></td>";

				html += "</tr>";

				html_ar.push(html);
			}

			$('.table tbody').html(html_ar.join(''));
		} else {
			$('.table tbody').html("<tr><td>Ничего не найдено</td></tr>");
		}

		viewPagination(data);
	}

	function viewPagination(data) {
		if (data['total'] == 0) {
			$('.pagination').hide();
			$('.pagination').remove();
			return;
		}

		var page = 1;
		if (data['page'] < 1) {
			page = 1;
		} else {
			page = data['page'];
		}

		if (!data['limit']) {
			limit = 10;
		} else {
			limit = data['limit'];
		}

		var num_links = data['total'];
		var num_pages = Math.ceil(data['total'] / limit);

		var output = '';

		if (page > 1) {
			output += "<li><a href='javascript:void(0);' data-page='1'>|<</a></li><li><a href='javascript:void(0);' data-page='"+(page-1)+"'><</a></li>";
		}

		if (num_pages > 1) {
			if (num_pages <= num_links) {
				var start = 1;
				var end = num_pages;
			} else {
				var start = page - Math.floor(num_links / 2);
				var end = page + Math.floor(num_links / 2);

				if (start < 1) {
					end += Math.abs(start) + 1;
					start = 1;
				}

				if (end > num_pages) {
					start -= (end - num_pages);
					end = num_pages;
				}
			}

			if (start > 1) {
				output += '<li class="disabled active"><a>...</a></li>';
			}

			for (i = start; i <= end; i++) {
				if (page == i) {
					output += '<li class="disabled active"><a href="javascript:void(0);" data-page="'+i+'"><b>'+i+'</b></a></li>';
				} else {
					output += '<li><a href="javascript:void(0);" data-page="'+i+'">'+i+'</a></li>';
				}
			}

			if (end < num_pages) {
				output += '<li class="disabled active"><a>...</a></li>';
			}
		}

		if (page < num_pages) {
			output += '<li><a href="javascript:void(0);" data-page="'+(page+1)+'">></a></li><li><a href="javascript:void(0);" data-page="'+(num_pages)+'">>|</a></li>';
		}

		$('.pagination').html(output);
		$('.pagination').removeClass('hide');
	}
</script>
