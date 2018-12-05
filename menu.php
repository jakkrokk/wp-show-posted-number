<?php
$path = getClassPath();
require_once($path);
$Spn = new Spn();
$postedNumbers = $Spn->getPostedNumbers();
$postWordCount = $Spn->getPostWordLen();
?>


<link  href="<?php echo plugins_url('wp-show-post-numbers/bulma.min.css');?>" rel="stylesheet" type="text/css"/>
<link  href="<?php echo plugins_url('wp-show-post-numbers/style.css');?>" rel="stylesheet" type="text/css"/>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.pie.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<script src="<?php echo plugins_url('wp-show-post-numbers/graphs.js');?>"></script>
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
	var opt1 = CreateGraph.opt1;
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
	var opt2 = CreateGraph.opt1;
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
	var opt3 = CreateGraph.opt1;
	opt3.xaxis.ticks = ticks3.filter((el,idx,arr) => { m = Math.round(arr.length / 8,0); return idx % m == (m - 1)});
	$.plot($("#graph3"),dat3,opt3);
	$("#graph3").bind("plothover",CreateGraph.tooltip);


	var dat4 = [{
			label: 'Average',
			color: '#00D1B2',
			bars: { show: false },
			lines: { show: true },
			points: { show: true },
			data: [ <?php echo join(',',$postWordCount['graph']['avgByMonth']);?> ]
		},{
			label: 'Max',
			color: '#ff3860',
			bars: { show: false },
			lines: { show: true },
			points: { show: true },
			data: [ <?php echo join(',',$postWordCount['graph']['maxByMonth']);?> ]
		},{
			label: 'Min',
			color: '#ffdd57',
			bars: { show: false },
			lines: { show: true },
			points: { show: true },
			data: [ <?php echo join(',',$postWordCount['graph']['minByMonth']);?> ]
		}];
	var ticks4 = [ <?php echo join(',',$postWordCount['ticks']['byMonth']);?> ];
	var opt4 = CreateGraph.opt2;
	opt4.xaxis.ticks = ticks4.filter((el,idx,arr) => { m = Math.round(arr.length / 8,0); return idx % m == (m - 1)});
	$.plot($("#graph4"),dat4,opt4);
	$("#graph4").bind("plothover",CreateGraph.tooltip);

	var dat5 = [{
			label: 'Longer&nbsp;&nbsp;' + <?php echo $postWordCount['graph']['ratio']['longer'];?> + ' posts',
			color: '#ff3860',
			data: <?php echo $postWordCount['graph']['ratio']['longer'];?>
		},{
			label: 'Long&nbsp;&nbsp;' + <?php echo $postWordCount['graph']['ratio']['long'];?> + ' posts',
			color: '#ff9baf',
			data: <?php echo $postWordCount['graph']['ratio']['long'];?>
		},{
			label: 'Shorter&nbsp;&nbsp;' + <?php echo $postWordCount['graph']['ratio']['shorter'];?> + ' posts',
			color: '#00D1B2',
			data: <?php echo $postWordCount['graph']['ratio']['shorter'];?>
		},{
			label: 'Short&nbsp;&nbsp;' + <?php echo $postWordCount['graph']['ratio']['short'];?> + ' posts',
			color: '#99ece0',
			data: <?php echo $postWordCount['graph']['ratio']['short'];?>
		}];
	var opt5 = CreateGraph.opt3;
	$.plot($("#graph5"),dat5,opt5);
});

</script>

<h1 class="header">
	<div class="container">
		<h1 class="title"># Show Posted Numbers</h1>
		<hr style="margin-bottom:0.5rem;">
	</div>
</h1>

<section class="section">
<div class="container">
	<div class="columns">
		<div class="column">
			<h3 class="subtitle"># Total Posts</h3>
			<div class="notification is-primary">
				<?php echo $postedNumbers['status']['counts'];?> posts
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># First Post</h3>
			<div class="notification is-warning">
				<?php echo date('Y/m/d',strtotime($postedNumbers['status']['first']));?>
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># Last Post</h3>
			<div class="notification is-warning">
				<?php echo date('Y/m/d',strtotime($postedNumbers['status']['last']));?>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column">
			<h3 class="subtitle"># Total days</h3>
			<div class="notification is-primary">
				<?php echo $postedNumbers['status']['post_range'];?> days
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># Post days</h3>
			<div class="notification is-info">
				<?php echo $postedNumbers['status']['post_day'];?> days
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># No post days</h3>
			<div class="notification is-danger">
				<?php echo $postedNumbers['status']['no_post_day'];?> days
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column">
			<h3 class="subtitle"># Max Post Text</h3>
			<div class="notification is-primary">
				<?php echo $postWordCount['status']['max'];?> chars
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># Min Post Text</h3>
			<div class="notification is-info">
				<?php echo $postWordCount['status']['min'];?> chars
			</div>
		</div>
		<div class="column">
			<h3 class="subtitle"># Average Post Text</h3>
			<div class="notification is-danger">
				<?php echo $postWordCount['status']['avg'];?> chars
			</div>
		</div>
	</div>
</div>
</section>

<section class="section">
<div class="container">
	<h2 class="title"># Daily posts</h2>
	<hr style="margin-bottom:0.5rem;">
	<div id="graph1" class="graph"></div>
</div>
</section>

<section class="section">
<div class="container">
	<div class="columns">
		<div class="column">
			<h2 class="title"># By Weekdays</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph2" class="graph container">

			</div>
		</div>
		<div class="column">
			<h2 class="title"># Total Posts</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph3" class="graph container">

			</div>
		</div>
	</div>
</div>
</section>


<section class="section">
<div class="container">
	<div class="columns">
		<div class="column">
			<h2 class="title"># Text Counts By Month</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph4" class="graph container">

			</div>
		</div>
		<div class="column">
			<h2 class="title"># Text Count Ratio</h2>
			<hr style="margin-bottom:0.5rem;">
			<div id="graph5" class="pie container">

			</div>
			<div class="">
				<table class="table is-bordered" style="float:right;">
					<tr><td class="is-primary">Shorter</td><td>- <?php echo $postWordCount['graph']['ratio_base']['short'];?></td></tr>
					<tr><td class="is-primary">Short</td><td><?php echo $postWordCount['graph']['ratio_base']['short'];?> - <?php echo $postWordCount['graph']['ratio_base']['avg'];?></td></tr>
					<tr><td class="is-danger">Long</td><td><?php echo $postWordCount['graph']['ratio_base']['avg'];?> -  <?php echo $postWordCount['graph']['ratio_base']['long'];?></td></tr>
					<tr><td class="is-danger">Longer</td><td><?php echo $postWordCount['graph']['ratio_base']['long'];?> -</td></tr>
				</table>
			</div>
		</div>
	</div>
</div>
</section>

<div id="tooltip"></div>
