function modSampleHeight(){
	var headHeight = 122;

	var sch = $("gantt-chart");
	sch.css({'height':(parseInt(document.body.offsetHeight)-headHeight)+"px"});
	var contbox = $("contbox");
	contbox.css({'width':(parseInt(document.body.offsetWidth)-300)+"px"});

	gantt.setSizes();
}
function LoadDateSelectors()
{
	$('#sdate').val(start_display_date);
	$('#edate').val(end_display_date);
}
	
function set_chart_range()
{
	$s = $('#sdate').val();
	$e = $('#edate').val();
	
	$s_ary = $s.split("/");
	$s_size = $s_ary.length;
	
	$e_ary = $e.split("/");
	$e_size = $e_ary.length;
	
	if($s_size != 3 || $e_size != 3)
	{
		$('#sdate').val(start_display_date);
		$('#edate').val(end_display_date);
		return
	}
		
	$s_month = $s_ary[0];
	$s_day = $s_ary[1];
	$s_year = $s_ary[2];
	$s_month = $s_month - 1;
	if($s_month == 0)
	{
		$s_month = '12';
		$s_year = $s_year - 1;
	}
		
	$e_month = $e_ary[0];
	$e_day = $e_ary[1];
	$e_year = $e_ary[2];
	$e_month = $e_month - 1;
	if($e_month == 0)
	{
		$e_month = '12';
		$e_year = $e_year - 1;
	}
	
	gantt.config.start_date = new Date($s_year, $s_month, $s_day);
	gantt.config.end_date = new Date($e_year, $e_month, $e_day);
	gantt.render();
}

gantt.attachEvent("onBeforeTaskDisplay", function(id, task){
	if (gantt_filter)
		if (task.priority != gantt_filter)
			return false;
	
	return true;
});

gantt.templates.scale_cell_class = function(date){
    if(date.getDay()==0||date.getDay()==6){
        return "weekend";
    }
};
gantt.templates.task_cell_class = function(item,date){
    if(date.getDay()==0||date.getDay()==6){ 
        return "weekend" ;
    }
};

var gantt_filter = 0;
function filter_tasks(node){
	gantt_filter = node.val();
	gantt.refreshData();
}

function show_scale_options(mode){
	var hourConf = $("filter_hours"),
		dayConf = $("filter_days");
	if(mode == 'day'){
		hourConf.css({"display":"none"});
		dayConf.css({"display":""});
		$("input", dayConf).prop('checked', true);
	}else if(mode == "hour"){
		hourConf.css({"display":""});
		dayConf.css({"display":"none"});
		$("input", hourConf).prop('checked', true);
	}else{
		hourConf.css({"display":"none"});
		dayConf.css({"display":"none"});
	}
}
function set_scale_units(mode){
	if(mode && mode.getAttribute){
		mode = mode.getAttribute("value");
	}

	switch (mode){
		case "work_hours":
			gantt.config.subscales = [
				{unit:"hour", step:1, date:"%H"}
			];
			gantt.ignore_time = function(date){
				if(date.getHours() < 9 || date.getHours() > 16){
					return true;
				}else{
					return false;
				}
			};

			break;
		case "full_day":
			gantt.config.subscales = [
				{unit:"hour", step:3, date:"%H"}
			];
			gantt.ignore_time = null;
			break;
		case "work_week":
			gantt.ignore_time = function(date){
				if(date.getDay() == 0 || date.getDay() == 6){
					return true;
				}else{
					return false;
				}
			};

			break;
		default:
			gantt.ignore_time = null;
			break;
	}
	gantt.refreshData();
	gantt.render();
}


function zoom_tasks(node){
	switch(node.value){
		case "week":
			gantt.config.scale_unit = "day"; 
			gantt.config.date_scale = "%d %M"; 

			gantt.config.scale_height = 60;
			gantt.config.min_column_width = 30;
			gantt.config.subscales = [{unit:"hour", step:1, date:"%H"}];
			show_scale_options("hour");
		break;
		case "trplweek":
			gantt.config.min_column_width = 70;
			gantt.config.scale_unit = "day"; 
			gantt.config.date_scale = "%d %M"; 
			gantt.config.subscales = [ ];
			gantt.config.scale_height = 35;
			show_scale_options("day");
		break;
		case "month":
			gantt.config.min_column_width = 70;
			gantt.config.scale_unit = "week"; 
			gantt.config.date_scale = "Week #%W"; 
			gantt.config.subscales = [{unit:"day", step:1, date:"%D"}];
			show_scale_options();
			gantt.config.scale_height = 60;
		break;
		case "year":
			gantt.config.min_column_width = 70;
			gantt.config.scale_unit = "month"; 
			gantt.config.date_scale = "%M"; 
			gantt.config.scale_height = 60;
			show_scale_options();
			gantt.config.subscales = [{unit:"week", step:1, date:"#%W"}];
		break;
	}
	set_scale_units();
	gantt.refreshData();
	gantt.render();
}

show_scale_options("day");
gantt.config.details_on_create = true;

gantt.templates.task_class = function(start, end, obj){
	return obj.project ? "project" : "";
};



gantt.locale.labels["section_priority"] = "Priority";
gantt.config.lightbox.sections = [
    {name: "description", height: 38, map_to: "text", type: "textarea", focus: true},
    {name: "priority", height: 22, map_to: "priority", type: "select", options: [
    	{key:"1", label: "Low"}, 
    	{key:"0", label: "Normal"}, 
    	{key:"2", label: "High"} ]},
    {name: "time", height: 72, type: "duration", map_to: "auto", time_format:["%d","%m","%Y","%H:%i"]}
];

<!--custom config bits-->
gantt.templates.tooltip_text = function(start,end,task){
	return "<b>Task:</b> "+task.text+"<br/><b>Active:</b> "+task.active+"<br/><b>Nested:</b> "+task.nested+"<br/><b>Actual Start:</b> "+task.actual_start+"<br/><b>Actual End:</b> "+task.actual_end+"<br/><b>Planned Start:</b> "+task.planned_start+"<br/><b>Planned End:</b> "+task.planned_end+"<br/><b>Task Type:</b> "+task.task_type+"<br/><b>Progress:</b> "+task.progress+"<br/><b>Comments:</b> "+task.comments+"<br/><b>Material Cost:</b> "+task.material_cost+"<br/><b>Labor Cost:</b> "+task.labor_cost+"<br/><b>Equipment Cost:</b> "+task.equipment_cost+"<br/><b>Subcontractor Cost:</b> "+task.subcontractor_cost+"<br/><b>Duration:</b> "+task.duration;
};

gantt.config.readonly = true;

var date_to_str = gantt.date.date_to_str(gantt.config.task_date);

var marker_id = gantt.addMarker({ start_date: new Date(), css: "today", text: "Today", title:date_to_str( new Date())});
setInterval(function(){
	var today = gantt.getMarker(marker_id);
	today.start_date = new Date();
	today.title = date_to_str(today.start_date);
	gantt.updateMarker(marker_id);
}, 1000*60);

gantt.config.columns = [
    {name:"id", label:"ID", align: "center", width:"30"},
	{name:"stat", label:"S", template:function(obj){
		return "<font color='" + obj.p_color + "' size='+3'>" + obj.stat + "</font>";
	}, align: "center", width:"30"},
    {name:"text", label:"Task name", width:"*", tree:true },
	{name:"task_type", label:"Task Type", width:"60"},
    {name:"progress", label:"Status", template:function(obj){
		return "<font color='" + obj.p_color + "'>" + Math.round(obj.progress*100) + "%" + "</font>";
    }, align: "center", width:40 }
];
gantt.config.grid_width = 390;

gantt.attachEvent("onTaskCreated", function(obj){
	obj.duration = 4;
	obj.progress = 0.25;
});

$(function(){
	gantt.config.fit_tasks = true;
	gantt.init("gantt-chart"); 
	gantt.refreshData();
	gantt.parse(tasks);
	LoadDateSelectors();
});

$(window).resize(function(e){
	modSampleHeight();
});