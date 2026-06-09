	<script src="/assets/js/jquery-1.11.1.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/chart.min.js"></script>
	<script src="/assets/js/chart-data.js"></script>
	<script src="/assets/js/easypiechart.js"></script>
	<script src="/assets/js/easypiechart-data.js"></script>
	<script src="/assets/js/bootstrap-datepicker.js"></script>
	<script src="/assets/js/bootstrap-table.js"></script>
	<script src="/assets/js/sweetalert2.min.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
				$(this).find('em:first').toggleClass("glyphicon-minus");	  
			}); 
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>
	
	<?php $success = session()->getFlashdata('success'); if ($success) : ?>
		<script type="text/javascript">
			$(document).ready(function() {
				swal("Success!", "<?= $success ?>", "success");
			});
		</script>
	<?php endif; ?>
	<?php $error = session()->getFlashdata('error'); if ($error) : ?>
		<script type="text/javascript">
			$(document).ready(function() {
				swal("Sorry!", "<?= $error ?>", "error");
			});
		</script>
	<?php endif; ?>
	<?php $warning = session()->getFlashdata('warning'); if ($warning) : ?>
		<script type="text/javascript">
			$(document).ready(function() {
				swal("Warning!", "<?= $warning ?>", "warning");
			});
		</script>
	<?php endif; ?>
	<?php $info = session()->getFlashdata('info'); if ($info) : ?>
		<script type="text/javascript">
			$(document).ready(function() {
				swal("Info!", "<?= $info ?>", "info");
			});
		</script>
	<?php endif; ?>
</body>

</html>
