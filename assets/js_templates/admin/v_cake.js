// var save_method;
// var table;

// $(document).ready(function(){
// 	// token begin
// 	$.ajaxSetup({data: {csrf_test_name: CFG.token}});
//     $(document).ajaxSuccess(function(e,x) {
//         var result = $.parseJSON(x.responseText);
//         $('input:hidden[name="csrf_test_name"]').val(CFG.token);
//         $.ajaxSetup({data: {csrf_test_name: result.token}});
//     });
// 	// token end

// 	$.get(CFG.url+"admin/get_reporte_bimestral", function(data) {
// 		// var tr = $.parseJSON(data);
// 		// $('#programarme_tbody').html(tr.lib);
// 	});

$dataPoints1 = array(
	array("label": "2010", "y": 36.12),
	array("label": "2011", "y": 34.87),
	array("label": "2012", "y": 40.30),
	array("label": "2013", "y": 35.30),
	array("label": "2014", "y": 39.50),
	array("label": "2015", "y": 50.82),
	array("label": "2016", "y": 74.70)
);
$dataPoints2 = array(
	array("label": "2010", "y": 64.61),
	array("label": "2011", "y": 70.55),
	array("label": "2012", "y": 72.50),
	array("label": "2013", "y": 81.30),
	array("label": "2014", "y": 63.60),
	array("label": "2015", "y": 69.38),
	array("label": "2016", "y": 98.70)
);



// });
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Average Amount Spent on Real and Artificial X-Mas Trees in U.S."
	},
	legend:{
		cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "Real Trees",
		indexLabel: "{y}",
		yValueFormatString: "$#0.##",
		showInLegend: true,
		dataPoints: $dataPoints1,
	},{
		type: "column",
		name: "Artificial Trees",
		indexLabel: "{y}",
		yValueFormatString: "$#0.##",
		showInLegend: true,
		dataPoints: $dataPoints2,
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}