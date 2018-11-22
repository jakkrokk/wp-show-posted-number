var CreateGraph = {
	opt : {
		lines : {
			lineWidth : 1,
			fill : true,
			fillColor : {
				colors : [{
						opacity : 0.3,
					}, {
						opacity : 0.4,
					}
				]
			},
		},
		bars : {
			show : false,
			barWidth : 0.8,
			align : 'center',
			lineWidth : 0,
			fillColor : {
				colors : [{
						opacity : 0.6,
					}, {
						opacity : 0.7,
					}
				]
			},
		},
		grid : {
			show : true,
			aboveData : false,
			labelMargin : 5,
			axisMargin : 0,
			borderWidth : 1,
			minBorderMargin : 5,
			clickable : true,
			hoverable : true,
			autoHighlight : true,
			mouseActiveRadius : 20,
			borderColor : '#ccc',
		},
		legend : {
			show : false,
		},
		yaxes : [
			{
				position : 'left',
				tickColor : '#ccc',
				font : {
					color : '#666'
				},
				min:0,
				minTickSize:1,
				tickDecimals: 'number',
			},
		],
		xaxis : {
			tickColor : '#ccc',
			font : {
				color : '#666'
			},
		},
	},
	tooltip : function (event,pos,item) {
		if (item) {
			var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2),
				d = item.series.data[item.dataIndex][2];
			$("#tooltip").html('<div>Date: ' + d + '<br />Count: ' + parseInt(y) + "</div>")
				.css({top: item.pageY - 72, left: item.pageX - 240})
				.fadeIn(50);
		} else {
			$("#tooltip").hide();
		}
	},
}
