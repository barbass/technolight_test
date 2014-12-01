<form method='GET' action='<?php echo $action['form'];?>'>
	<table class='table'>
		<tr>
			<th>Номер</th>
			<th>Слово</th>
			<th>Язык</th>
			<th>Действие</th>
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
		};
	});

	/**
	 * Получение списка переводов
	 * @param int
	 */
	function getTranslate(word_id) {

	}
</script>
