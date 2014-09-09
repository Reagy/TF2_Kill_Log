<?php 
//Include Config
include "inc/config.php";
?>
<?php include("inc/nav.php"); ?>
		<div class="stats-body">
			<div style="text-align:center;margin-top:20px">
				<h1 style="display: inline">Top Ten Killers for </h1>
				<div class="container">
					<div class="row">
						<div class="col-sm-4"></div>
						<div class="col-sm-4">
							<h4>
								<div id="reportrange" style="cursor:pointer;">
									<span><?php echo date("F j, Y", strtotime('-7 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b>
								</div>
							</h4>
						</div>
						<div class="col-sm-4"></div>
					</div>
				</div>
			</div>
			<br>
			<div id='killers' style='height:300px;border-bottom:1px solid #222222;cursor:pointer'></div>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>
<script type="text/javascript">
	function data(dates){
			$.ajax({
				type: "GET",
				dataType: 'json',
				url: "inc/gettoprange.php", // This is the URL to the API
				data: "id=" + dates,
				success: function(msg){
					chart.setData(msg);
				}
			})
		}
	$('#reportrange').daterangepicker(
		{
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			minDate: moment().subtract(2, 'month'),
			maxDate: moment(),
			startDate: moment().subtract(6, 'days'),
			endDate: moment()
		},
		function(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}
	);
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		var dates = picker.startDate.format('YYYY-M-D')+","+picker.endDate.format('YYYY-M-D');
		data(dates);
	});
	var chart =  Morris.Bar ({
		element: 'killers',
		data: data(moment().subtract(6, 'days').format('YYYY-M-D')+","+moment().format('YYYY-M-D')),
		xkey: 'name',
		ykeys: ['kills'],
		labels: ['Kills'],
		gridTextColor: ['#222222'],
		barColors: ['#222222', '#e74c3c', '#c0392b'],
		barRatio: 0.4,
		barSizeRatio: 0.66,
		xLabelAngle: 0,
		hideHover: 'auto',
		resize: true
	}).on('click', function(i, row){
		console.log(i, row);
		window.location = "player.php?id="+row['auth'];
	});
</script>
</body>
</html>