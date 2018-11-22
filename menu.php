<?php
$path = getClassPath();
require_once($path);
$Spn = new Spn();
$postedNumbers = $Spn->getPostedNumbers();
?>
<link  href="<?php echo plugins_url('show-post-numbers/bulma.min.css');?>" rel="stylesheet" type="text/css"/>
<link  href="<?php echo plugins_url('show-post-numbers/style.css');?>" rel="stylesheet" type="text/css"/>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.pie.min.js"></script>
<script src="<?php echo plugins_url('show-post-numbers/graphs.js');?>"></script>
<script type="text/javascript">

$(function() {
	var dat1 = [{
		label: 'Posted Numbers',
		color: '#00D1B2',
		bars: { show: true, order: 0 },
		lines: { show: false },
		points: { show: false },
		data: [ <?php echo join(',',$postedNumbers['raws']);?> ]
	}];
	var ticks1 = [ <?php echo join(',',$postedNumbers['ticks']);?> ];
	var opt1 = CreateGraph.opt;
	opt1.xaxis.ticks = ticks1.filter((el,idx,arr) => { m = Math.round(arr.length / 10,0); return idx % m == (m - 1)});
	$.plot($("#graph1"),dat1,opt1);
	$("#graph1").bind("plothover",CreateGraph.tooltip);


	var dat2 = [{
		label: 'Weekday Posts',
		color: '#ff3860',
		bars: { show: true, order: 0 },
		lines: { show: false },
		points: { show: false },
		data: [ <?php echo join(',',$postedNumbers['weekdayRaw']);?> ]
	}];
	var ticks2 = [ <?php echo join(',',$postedNumbers['weekdayLabel']);?> ];
	var opt2 = CreateGraph.opt;
	opt2.xaxis.ticks = ticks2;
	$.plot($("#graph2"),dat2,opt2);
	$("#graph2").bind("plothover",CreateGraph.tooltip);


	var dat3 = [{
		label: 'Total Posts',
		color: '#ffdd57',
		bars: { show: false },
		lines: { show: true },
		points: { show: true },
		data: [ <?php echo join(',',$postedNumbers['sum']);?> ]
	}];
	var ticks3 = [ <?php echo join(',',$postedNumbers['ticks']);?> ];
	var opt3 = CreateGraph.opt;
	opt3.xaxis.ticks = ticks3.filter((el,idx,arr) => { m = Math.round(arr.length / 8,0); return idx % m == (m - 1)});
	$.plot($("#graph3"),dat3,opt3);
	$("#graph3").bind("plothover",CreateGraph.tooltip);

});

</script>

<h1 class="header">
	<div class="container">
		<h1 class="title">Show Posted Numbers</h1>
		<hr style="margin-bottom:0.5rem;">
	</div>
</h1>

<section class="section">
<div class="container">
	<div class="columns">
		<div class="column">
			<h3 class="subtitle">Total Posts</h3>
			<div class="notification is-primary">
				<?php echo $postedNumbers['status']['counts'];?> posts
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle">First Post</h3>
			<div class="notification is-warning">
				<?php echo date('Y/m/d',strtotime($postedNumbers['status']['first']));?>
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle">Last Post</h3>
			<div class="notification is-warning">
				<?php echo date('Y/m/d',strtotime($postedNumbers['status']['last']));?>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column">
			<h3 class="subtitle">Total days</h3>
			<div class="notification is-primary">
				<?php echo $postedNumbers['status']['post_range'];?> days
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle">Post days</h3>
			<div class="notification is-info">
				<?php echo $postedNumbers['status']['post_day'];?> days
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle">No post days</h3>
			<div class="notification is-danger">
				<?php echo $postedNumbers['status']['no_post_day'];?> days
			</div>
		</div>
	</div>
</div>
</section>

<section class="section">
<div class="container">
	<h2 class="title">Daily posts</h2>
	<hr style="margin-bottom:0.5rem;">
	<div id="graph1" class="graph"></div>
</div>
</section>

<section class="section">
<div class="container">
	<div class="columns">
		<div class="column">
			<h2 class="title">By Weekdays</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph2" class="graph container">

			</div>
		</div>
		<div class="column">
			<h2 class="title">Total Posts</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph3" class="graph container">

			</div>
		</div>
	</div>
</div>
</section>


<div id="tooltip"></div>
