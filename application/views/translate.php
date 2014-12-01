<form method='GET' action='<?php echo $action['form'];?>'>
	<table class='table'>
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
				<input type='text' class='form-control' name='word' value='<?php echo (!empty($form['word'])) ? $form['word']: '';?>' >
			</th>
			<th></th>
			<th></th>
		</tr>


		<?php if ($words) { ?>
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
</form>

<?php echo $pages;?>

<script type='text/javascript'>
	$(document).ready(function() {
		$('.get_translate').on('click', function() {
			var word_id = $(this).attr('data-word_id');
			getTranslate(word_id);
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
</script>
