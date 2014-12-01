<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title><?php echo (isset($title)) ? $title : '';?></title>

		<link rel="stylesheet" href="<?php echo base_url("resource/css/style-admin.css");?>">

		<script src="https://yandex.st/jquery/1.8.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url("resource/js/jquery-1.8.3.min.js");?>"><\/script>')</script>

		<link rel="stylesheet" href="<?php echo base_url("resource/css/bootstrap-3.1.0.min.css");?>">
		<script type='text/javascript' src="https://yandex.st/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<!-- Неточное определение успешной загрузки bootstrap -->
		<script>window.jQuery.fn.carousel || document.write('<script src="<?php echo base_url("resource/js/bootstrap-3.1.0.min.js");?>"><\/script>')</script>

		<link rel="stylesheet" href="<?php echo base_url("resource/js/jquery-ui-1.10.4/css/ui-lightness/jquery-ui-1.10.4.min.css");?>">
		<script src="https://yandex.st/jquery-ui/1.10.4/jquery-ui.min.js"></script>
		<script>window.jQuery.ui || document.write('<script src="<?php echo base_url("resource/js/jquery-ui-1.10.4/js/jquery-ui-1.10.4.min.js");?>"><\/script>')</script>


		<script type='text/javascript' src="https://yandex.st/jquery-ui/1.10.4/jquery.ui.datepicker.min.js"></script>
		<script>window.jQuery.ui.datepicker || document.write('<script src="<?php echo base_url("resource/js/jquery-ui-1.10.4/development-bundle/ui/minified/jquery.ui.datepicker.min.js");?>"><\/script>')</script>

		<script src="<?php echo base_url("resource/js/common/functions-1.0.9.js");?>"></script>
		<script src="<?php echo base_url("resource/js/common/payment-1.0.0.js");?>"></script>
		<script src="<?php echo base_url("resource/js/common/events-1.0.1.js");?>"></script>

		<?php
			// Загружаем стили
			if (isset($styles)) {
				foreach($styles as $s) {
					echo "<link rel='stylesheet' href='".$s."'>";
				}
			}
			// Загружаем js-скрипты
			if (isset($js)) {
				foreach($js as $j) {
					echo "<script type='text/javascript' src='".$j."'></script>";
				}
			}
		?>
	</head>

	<body>

	<!-- Шапка -->
	<div id="header">
		<div role="navigation" class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="sr-only"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?php echo $url['project'];?>" class="navbar-brand"><?php echo $text['project_name'];?></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php if (!empty($user_info)) { ?>
							<li>
								<a href="<?php echo $url['account'];?>">
									<?php echo $text['user'];?>
									<?php echo $user_info['lastname'].' ('.$user_info['login'].')';?>
								</a>
							</li>
						<?php } ?>
						<?php if ($language_list) { ?>
							<li class="dropdown">
								<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
									<?php echo $text['language'];?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu inverse">
									<?php foreach($language_list as $l) { ?>
										<li class="<?php echo ($l['active']) ? 'disabled' : '';?>">
											<a <?php if(!$l['active']) echo "href='".$l['href']."'";?> >
												<?php echo $l['name'];?>
											</a>
										</li>
										<li class="divider"></li>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>

						<li><a href="<?php echo $url['authorized'];?>"><?php echo $text['authorized'];?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id='ajaxLoading' class='hide'>
		<button type="button" class="close" aria-hidden="true">&times;</button>
		<div class="progress progress-striped active">
			<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
				<span class="sr-only">100% Complete</span>
			</div>
		</div>
		<span><?php echo (!empty($text['ajax_loading'])) ? $text['ajax_loading'] : 'Ajax loading...';?></span>
	</div>

	<div class="container-fluid">
		<div class="row">
