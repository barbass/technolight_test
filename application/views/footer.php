		</div>
	</div>
	<!-- Шапка -->

	<footer>
		<div class='container'>
			<p class='text-muted'>
				<span><?php echo $telephone;?> &nbsp; <a href="mailto:<?php echo $support;?>"><?php echo $support;?></a></span>
			</p>
		</div>
	</footer>

	<script type='text/javascript'>
		$(document).ready(function() {
			$('input[type = datetime]').datepicker({
				dateFormat: "yy-mm-dd",
				constrainInput: false,
				changeMonth: true,
				changeYear: true,
			});
		});
	</script>

	</body>

</html>
