$(document).ready(
	function() {
		var interval=[];
		var resize_timeout;
		var message_timeout
		var window_width=$(window).width();
		var mobile_xticks=5;
		var desktop_xticks=11;
		var mobile_switch_width=641;
		var xticks;
		make_main_interval();
		$(".button").click(function(){
			var el=$(this);
			var vid=$(this).attr("vid");
			switch (el.attr("action")) {
				case 'forceupdate': 
					call_script('ajax_ze_action.php?action=updatesoc&vid='+vid);
					break;
				case 'chargebat':
					call_script('ajax_ze_action.php?action=chargebat&vid='+vid);
					break;
				case 'aircon':
					call_script('ajax_ze_action.php?action=aircon&vid='+vid);
					break;
			}
		});
		$("body").on("change","select",function(){
			var option=$(this).find(":selected");
			var el=$(this).parents(".vehicle");
			var arrayLength = interval.length;
			for (var i = 0; i < arrayLength; i++) {
				clearInterval(interval[i]);
			}
			$(".button.get_soc").fadeIn(300);
			get_soc_history(el.attr("id"),option.attr("start"),option.attr("end"));
		});
		$(".button.get_soc").click(function(){
			make_main_interval();
			$(this).fadeOut(300);
		});
		function call_script(script) {
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: script,
				data: '',
				success: function(htmla) {
					$(".vehicle").find(".message").html(htmla).animate({'right':'0px'},300,function(){
						message_timeout=setTimeout(function() {
							$(".vehicle").find(".message").html(htmla).animate({'right':'-300px'},300);
							}, 6000);
					});
				}
			});
		}
		function update_soc(vid) {
			window_width=$(window).width();
			$.ajax({
				type: "GET",
				url: "ajax_get_actual_ze_values_vid.php",
				data: 'vid='+vid,
				dataType: 'json',
				success: function(daten) {
					$(".vehicle#"+vid).find(".soc").css({'width':daten.charge_level+'%'});
					$(".vehicle#"+vid).find(".text").html(daten.charge_level+' %'+ daten.last_update_string+'</span>');
					$(".vehicle#"+vid).find(".reichweite .wert").html(daten.reichweite+' km');
					$(".vehicle#"+vid).find(".reichweite100 .wert").html(daten.reichweite100+' km');
					$(".vehicle#"+vid).find(".plugged .wert").html(daten.plugged);
					$(".vehicle#"+vid).find(".charging .wert").html(daten.charging);
					$(".vehicle#"+vid).find(".charging_point .wert").html(daten.charging_point);
					$(".vehicle#"+vid).find(".remaining_time .wert").html(daten.remaining_time+' Min');
					$("#ergebnisliste").html(daten.history_liste);
					if (daten.charging=='ja') {
						$(".vehicle#"+vid).find(".hidden").slideDown(200);
						$(".vehicle#"+vid).attr("charging",1);
					} else {
						$(".vehicle#"+vid).find(".hidden").slideUp(200);
						$(".vehicle#"+vid).attr("charging",0);
					}
					if (window_width<mobile_switch_width) {
						xticks=mobile_xticks;
					} else {
						xticks=desktop_xticks;
					}
					d1=daten['soc'];
					d2=daten['power'];
					var plot = $("#soc_plot").plot(
						[ 	
							{ 
								data: d1,
								lines: { fill: true, show: true, fillColor: "rgba(0, 255, 0, 0.6)" },
								points: { fill: true, show: true}
							},{ 
								data: d2,
								lines: { fill: false }
							}
						],
						{ 
							yaxis: { min: 0 , max: 100 , ticks: 11 },
							xaxis: { mode: "time", timeformat: "%d.%m. %H:%M",ticks:xticks },
							colors: ["#0f0","#f00" ]
						}						
					);
					$("#ladungen .pulldown").html(daten['ladungen']);
					

				}
			});
		}
		function make_main_interval() {
			$(".vehicle").each(function(i){
				var el=$(this);
				interval[i]=setInterval(function(){
					update_soc(el.attr("id"));
				},20000);
				update_soc(el.attr("id"));
			});
		}
		$(window).resize(function(){
			clearTimeout(resize_timeout);
			resize_timeout=setTimeout(function(){
				$(".vehicle").each(function(i){
					get_soc_history($(this).attr("id"),0,0);
				});
			},500);
		});
		function get_soc_history(vid,start,end) {
			if (start>0) {
				var zeit='&start='+start+'&end='+end;
			} else {
				var zeit='';
			}
			var charge=$(".vehicle#"+vid).attr("charging");
			window_width=$(window).width();
			$.ajax({
				type: "GET",
				url: "ajax_get_soc_history.php",
				data: 'vid='+vid+'&charging='+charge+zeit,
				dataType: 'json',
				success: function(daten) {
					if (window_width<mobile_switch_width) {
						xticks=mobile_xticks;
					} else {
						xticks=desktop_xticks;
					}
					d1=daten['soc'];
					d2=daten['power'];
					var plot = $("#soc_plot").plot(
						[ 	
							{ 
								data: d1,
								lines: { fill: true, show: true, fillColor: "rgba(0, 255, 0, 0.6)" },
								points: { fill: true, show: true}
							},{ 
								data: d2,
								lines: { fill: false }
							}
						],
						{ 
							yaxis: { min: 0 , max: 100 , ticks: 11 },
							xaxis: { mode: "time", timeformat: "%d.%m. %H:%M",ticks:xticks },
							colors: ["#0f0","#f00" ]
						}						
					);
					$("#ladungen .pulldown").html(daten['ladungen']);
				}
			});
		}
	});

