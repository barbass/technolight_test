<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title><?php echo (isset($title)) ? $title : '';?></title>

		<script src="https://yandex.st/jquery/1.8.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url("resource/js/jquery-1.8.3.min.js");?>"><\/script>')</script>

		<link rel="stylesheet" href="<?php echo base_url("resource/css/bootstrap-3.1.0.min.css");?>">
		<script type='text/javascript' src="https://yandex.st/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<!-- Неточное определение успешной загрузки bootstrap -->
		<script>window.jQuery.fn.carousel || document.write('<script src="<?php echo base_url("resource/js/bootstrap-3.1.0.min.js");?>"><\/script>')</script>

		<link rel="stylesheet" href="<?php echo base_url("resource/js/jquery-ui-1.10.4/css/ui-lightness/jquery-ui-1.10.4.min.css");?>">
		<script src="https://yandex.st/jquery-ui/1.10.4/jquery-ui.min.js"></script>
		<script>window.jQuery.ui || document.write('<script src="<?php echo base_url("resource/js/jquery-ui-1.10.4/js/jquery-ui-1.10.4.min.js");?>"><\/script>')</script>
	</head>

	<body>

	<!-- Шапка -->
	<div id="header">

	</div>

	<div class="container-fluid">
		<div class="row">
			<?php if (!empty($error)) { ?>
				<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $error;?>
				</div>
			<?php } ?>

			<?php if (!empty($success)) { ?>
				<div class="alert alert-dismissable alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $success;?>
				</div>
			<?php } ?>

			<?php echo (isset($content)) ? $content : '';?>
		</div>
	</div>

	<footer>
		<div class='container'>
			<p class='text-muted'>
				<span><a href="mailto:barbass1025@gmail.com">barbass1025@gmail.com</a></span>
			</p>
		</div>
	</footer>

	</body>
</html>
