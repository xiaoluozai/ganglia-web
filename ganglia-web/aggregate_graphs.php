<style>
#aggregate_graph_table_form {
   font-size: 12px;
}
</style>
<script>
$("#hreg").change(function() {
  $.cookie("ganglia-aggregate-graph-hreg" + window.name,
	   $("#hreg").val());
});

$("#aggregate_graph_table_form input[name=gtype]").change(function() {
  $.cookie("ganglia-aggregate-graph-gtype" + window.name,
	   $("#aggregate_graph_table_form input[name=gtype]:checked").val());
});

function restoreAggregateGraph() {
  var hreg = $.cookie("ganglia-aggregate-graph-hreg" + window.name);
  if (hreg != null)
    $("#hreg").val(hreg);
  
  var gtype = $.cookie("ganglia-aggregate-graph-gtype" + window.name);
  if (gtype != null)
    $("#aggregate_graph_table_form input[name=gtype]").val([gtype]);
  
  var metric = $.cookie("ganglia-aggregate-graph-metric" + window.name);
  if (metric != null)
    $("#metric_chooser").val(metric);
  
  if (hreg != null && gtype != null && metric != null)
    return true;
  else
    return false;
}

function createAggregateGraph() {
  $("#aggregate_graph_display").html('<img src="img/spinner.gif">');
  $.get('graph_all_periods.php', $("#aggregate_graph_form").serialize() + "&aggregate=1" , function(data) {
    $("#aggregate_graph_display").html(data);
  });
  return false;
}

var availableMetrics = [ <?php
  require_once('./cache.php');
  foreach ( $index_array['metrics'] as $key => $value)
    $metrics[] = "'" . $key . "'";

  print join(',', $metrics);
  ?>
];

$(function() {
    $( "#metric_chooser" ).autocomplete({
      source: availableMetrics,
      change: function(event, ui) {
	$.cookie("ganglia-aggregate-graph-metric" + window.name,
	         $("#metric_chooser").val());
      }
    });
  $( ".ag_buttons" ).button();
});

$(document).ready(function() {
  if (restoreAggregateGraph())
    createAggregateGraph();
});
</script>
<div id="aggregate_graph_header">
<h2>Create aggregate graphs</h2>

<form id="aggregate_graph_form">
<table id="aggregate_graph_table_form">
<tr>
<td>Host Regular expression e.g. web-[0,4], web or (web|db):</td>
<td colspan=2><input name="hreg[]" id="hreg" size=60></td>
<tr>
<tr><td>Metric (not a report e.g. load_one, cpu_system):</td>
<td colspan=2><input name="m" id="metric_chooser"></td>
</tr>
<tr>
<td>Graph Type:</td><td>
<div id="graph_type_menu"><input type="radio" name="gtype" value="line" checked>Line</input>
<input type="radio" name="gtype" value="stack">Stacked</input></div></td>
<td>
<button class="ag_buttons" onclick="storeAggregateGraph();createAggregateGraph(); return false">Create Graph</button></td>
</tr>
</table>
</form>
</div>
<div id="aggregate_graph_display">

</div>