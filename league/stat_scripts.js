function name_check(name) {
	if(name=='Newton Abbot, Ipplepen and Torbay Deaneries') {
		name = 'NAIT Deaneries';
	} else if(name=='Plymouth and Ivybridge Deaneries') {
		name = 'P&amp;I Deaneries';
	} else if(name=='Society of Royal Cumberland Youths') {
		name = 'SRCY';
	}
	return name;
}

function loc_check(comp, loc) {
	switch(comp) {
		case loc:
		case "Chittlehampton 6 Bell":
		case "Dowland 5 Bell":
		case "Eggbuckland":
		case "Kilkhampton 8 Bell":
		case "Kilkhampton 6 Bell":
		case "Stratton 8 Bell":
		case "Stratton 6 Bell":
		case "Bickleigh":
			return comp;
			break; // shouldn't get here!
		case "Chittlehampton 8 Bell":
			if($('#year_3').val()==1990) {
				return comp+' <span class="italic">(held at '+loc+')</span>';
			} else {
				return comp;
			}
			break; // shouldn't get here!
		default:
			return comp+' <span class="italic">(held at '+loc+')</span>';
			break; // shouldn't get here!
	}
}

function msie_post(php_file, data, post_process) {
	if($.browser.msie) {
		$.ajax({
			type: "POST",
			url: php_file,  // ! watch out for same
			dataType: "xml", // 'xml' passes it through the browser's xml parser
			data: data,
			success: function(xmlDoc, status) {
				post_process(xmlDoc);
			},
			complete: function(xhr, status) {
				if( status == 'parsererror' ) {
					xmlDoc = null;
					if(window.DOMParser){
						parser=new DOMParser();
						xmlDoc=parser.parseFromString( xhr.responseText,"text/xml" ) ;
					} else { // Internet Explorer
						xmlDoc=new ActiveXObject( "Microsoft.XMLDOM" ) ;
						xmlDoc.async = "false" ;
						xmlDoc.loadXML( xhr.responseText ) ;
					}
					post_process( xmlDoc ) ;
				}
			},
			error: function(xhr, status, error) {  //nothing
			}
		});
	} else {
		$.post(php_file, data, post_process);
	}
}


function tg_change() {
	function post_process(data){
		var content = "<option value=\"\">Select a team</option>\n";
		$(data).find('team').each(function(){
			content += "<option value=\""+$(this).text()+"\">"+$(this).text()+"</option>";
		});
		$('#team_menu').html(content);
	};
	if($('#tg').val()==0) {
		$('#team_menu').html('<option value="">Select a letter group first</option>');
	} else {
		var data = 'tg='+$('#tg').val()
		msie_post("archive_ajax_queries.php", data, post_process);
	}
}

function accordions () {
	$('#league_accord').accordion({
		header: 'h3',
		active: false,
		collapsible: true,
		autoHeight: false
	});
	
	$('#comp_accord').accordion({
		header: 'h3',
		active: false,
		collapsible: true,
		autoHeight: false
	});
	
	$('#team_accord').accordion({
		header: 'h3',
		active: false,
		collapsible: true,
		autoHeight: false
	});
	
	$('#ringer_accord').accordion({
		header: 'h3',
		active: false,
		collapsible: true,
		autoHeight: false
	});
	
	$('#search_accord').accordion({
		header: 'h3',
		active: false,
		collapsible: true,
		autoHeight: false
	});
}

function searches() {
	$('#team_search').click(function(){
		function post_process(data) {
			var content;
			// display team name and deanery
			content = "<h3>"+$(data).find('team').text()+"</h3>\n";
			switch($(data).find('deanery').text()) {
				case "Plymouth (Devonport, Moorside, Sutton)": case "Newton Abbot and Ipplepen":
					content += "<h4>"+$(data).find('deanery').text()+" Deaneries</h4>\n";
					break;
				case "None": case "Not Applicable":
					// do nothing
					break;
				case "Out of County":
					content += "<h4>Team from outside Devon</h4>\n";
					break;
				default:
					content += "<h4>"+$(data).find('deanery').text()+" Deanery</h4>\n";
					break;
			}
			$('#search_results_here').html(content);
			// get all time stats
			$(data).find('all-time-stats').text(function (){
				content = "<h4>All-time Stats</h4>\n<table>\n";
				for(var i=0; i<2; i++) {
					content += "<tr>\n";
					content += i==0 ? "<th>Years<br>Competed</th>\n" : "<td class=\"center\">"+$(this).find('year_range').text()+"</td>\n";
					content += i==0 ? "<th>Competitions</th>\n" : "<td class=\"center\">"+$(this).find('comps').text()+"</td>\n";
					content += i==0 ? "<th>Average<br>Ranking</th>\n" : "<td class=\"right\">"+$(this).find('ave_rank').text()+"</td>\n";
					content += i==0 ? "<th>Average<br>Position</th>\n" : "<td class=\"right\">"+$(this).find('ave_pos').text()+"</td>\n";
					content += i==0 ? "<th>Total<br>Faults</th>\n" : "<td class=\"right\">"+$(this).find('tot_faults').text()+"</td>\n";
					content += i==0 ? "<th>Average<br>Faults</th>\n" : "<td class=\"right\">"+$(this).find('ave_faults').text()+"</td>\n";
					content += i==0 ? "<th>All-time Fault<br>Difference</th>\n" : "<td class=\"right\">"+$(this).find('fault_diff').text()+"</td>\n";
					content += i==0 ? "<th>Total<br>Points</th>\n" : "<td class=\"right\">"+$(this).find('tot_points').text()+"</td>\n";
					content += i==0 ? "<th>Average<br>Points</th>\n" : "<td class=\"right\">"+$(this).find('ave_points').text()+"</td>\n";
					content += i==0 ? "<th>Total No<br>Results</th>\n" : "<td class=\"right\">"+$(this).find('nr').text()+"</td>\n";
					content += "</tr>\n";
				}
				content += "</table>\n";
			// display them
				$('#search_results_here').append(content);
			});
			// get seasonal stats
			$(data).find('seasonal-stats').text(function (){
				content = "<h4>Seasonal Stats</h4>\n<table>\n";
				
				$(this).find('season').first().text(function (){
					content += "<tr>\n";
					if($(this).find('year')) {
						content += "<th>Season</th>";
					}
					if($(this).find('no_of_comps').text()!="") {
						content += "<th>Competitions</th>\n";
					}
					if($(this).find('tot_faults').text()!="") {
						content += "<th>Total<br>Faults</th>\n";
					}
					if($(this).find('ave_pos').text()!="") {
						content += "<th>Average<br>Position</th>\n";
					}
					if($(this).find('ranking').text()!="") {
						content += "<th>Ranking</th>\n";
					}
					if($(this).find('ave_faults').text()!="") {
						content += "<th>Average<br>Faults</th>\n";
					}
					if($(this).find('nr').text()!="") {
						content += "<th>No<br>Results</th>\n";
					}
					if($(this).find('tot_points').text()!="") {
						content += "<th>Total<br>Points</th>\n";
					}
					if($(this).find('fault_diff').text()!="") {
						content += "<th>Fault<br>Difference</th>\n";
					}
					content += "<tr>\n";
				});
				
				
				$(this).find('season').each(function (){ 
					content += "<tr>\n";
					if($(this).find('year')) {
						content += "<td class=\"center\">"+$(this).find('year').text()+"</td>\n";
					}
					if($(this).find('no_of_comps').text()!="") {
						content += "<td class=\"center\">"+$(this).find('no_of_comps').text()+"</td>\n";
					}
					if($(this).find('tot_faults').text()!="") {
						content += "<td class=\"right\">"+$(this).find('tot_faults').text()+"</td>\n";
					}
					if($(this).find('ave_pos').text()!="") {
						content += "<td class=\"right\">"+$(this).find('ave_pos').text()+"</td>\n";
					}
					if($(this).find('ranking').text()!="") {
						content += "<td class=\"right\">"+$(this).find('ranking').text()+"</td>\n";
					}
					if($(this).find('ave_faults').text()!="") {
						content += "<td class=\"right\">"+$(this).find('ave_faults').text()+"</td>\n";
					}
					if($(this).find('nr').text()!="") {
						content += "<td class=\"right\">"+$(this).find('nr').text()+"</td>\n";
					}
					if($(this).find('tot_points').text()!="") {
						content += "<td class=\"right\">"+$(this).find('tot_points').text()+"</td>\n";
					}
					if($(this).find('fault_diff').text()!="") {
						content += "<td class=\"right\">"+$(this).find('fault_diff').text()+"</td>\n";
					}
					content += "</tr>\n";
				});
				content += "</table>\n";
			// display them
				$('#search_results_here').append(content);
			});
			// get results
			$(data).find('results').text( function() {
				content = "<h4>Collected Results</h4>\n";
				content += "<table>\n<tr>\n<th>Competition</th>\n<th>Position</th>\n<th>Faults</th>\n<th>Points</th>\n</tr>\n";
				$(this).find('competition').each(function() {
					content += "<tr>\n<td>"+$(this).find('event').text()+"</td>\n";
					content += "<td class=\"center\">"+$(this).find('position').text()+"</td>\n";
					content += "<td class=\"right\">"+$(this).find('faults').text()+"</td>\n";
					content += "<td class=\"right\">"+$(this).find('points').text()+"</td>\n</tr>\n";
				});
				content += "</table>\n";
				// display them
				$('#search_results_here').append(content);
			});
			
			
		}
		var team; // team name
		var res_flag = 2; // stats or results flag
		var year_range = 0; // year range
		var adv_flag = 0; // advanced options
		var start_year, end_year; // start year and end year from text boxes
		
		// check that there is a team to submit
		if($('#team_menu').val()=="") {
			window.alert("You need to select a team");
			return false;
		}
	
		// first check form for errors in text boxes:
		// - Both boxes must be numbers greater than whatever the first year is
		// - the end should be greater than or equal to the start
		// - Neither year can be in the future
		// work out the range for the php output
		if($('#start').val()!='') {
			start_year = parseInt($('#start').val());
			end_year = parseInt($('#end').val());
			if (isNaN(start_year) || isNaN(end_year)) { // cheeck for non numerical input
				window.alert("Please use numbers only");
				return false;
			} else if (start_year>year_start || end_year>year_start) { // is either year in the future?
				window.alert("Neither year can be in the future");
				return false;
			} else if (start_year<first_year)  { // check that the numbers are in the right range
				window.alert("The start year must be "+first_year+" or later");
				return false;
			} else if (end_year<start_year) {
				window.alert("The end year must not be before the start year");
				return false;
			}
			year_range = end_year - start_year + 1;
		} else {
			start_year = first_year;
			year_range = 0;
		}
	
		// result or stats or both?
		if($('#res0').attr('checked')) {
			res_flag = 0;
		} else if ($('#res1').attr('checked')) {
			res_flag = 1;
		} else if ($('#res2').attr('checked')) {
			res_flag = 2;
		}	
		// advanced options section
		
		for(var i=0; i<8; i++) {
			if($("#adv"+i).attr('checked')) {
				adv_flag += Math.pow(2,i);
			}
		}
		$('#search_results_here').html("<p>Loading please wait...</p>");

		// submit the details to the drl_team.php page
		var data = {
			team: $('#team_menu').val(),
			res: res_flag,
			range: year_range,
			start: start_year,
			adv: adv_flag
		};
		msie_post('archive_ajax_queries.php', data, post_process);	
		
		return false;
	});
	
	$('#comp_search_go').click( function(){
		function post_process(data) {
			var content;
			// display team name and deanery
			content = "<h3>"+$(data).find('competition').text()+" Competition Statistics</h3>\n";
			$('#search_results_here').html(content);
			// get all time stats
			$(data).find('all-time-stats').text(function (){
				content = "<h4>All-time Stats</h4>\n<table>\n";
				for(var i=0; i<2; i++) {
					content += "<tr>\n";
					content += i==0 ? "<th>No of<br>Events</th>\n" : "<td class=\"center\">"+$(this).find('events').text()+"</td>\n";
					content += i==0 ? "<th>Total<br>Faults</th>\n" : "<td class=\"right\">"+$(this).find('tot_faults').text()+"</td>\n";
					content += i==0 ? "<th>Average<br>Faults</th>\n" : "<td class=\"right\">"+$(this).find('ave_faults').text()+"</td>\n";
					content += i==0 ? "<th>Average Margin<br>of Victory</th>\n" : "<td class=\"right\">"+$(this).find('ave_margin').text()+"</td>\n";
					content += "</tr>\n";
				}
				content += "</table>\n";
			// display them
				$('#search_results_here').append(content);
			});
			// get seasonal stats
			$(data).find('seasonal-stats').text(function (){
				content = "<h4>Seasonal Stats</h4>\n<table>\n";
				$(this).find('season').first().text(function (){
					content += "<tr>\n";
					if($(this).find('year')) {
						content += "<th>Season</th>";
					}
					if($(this).find('location').text()!="") {
						content += "<th>Location</th>\n";
					}
					if($(this).find('no_of_teams').text()!="") {
						content += "<th>Number<br>of teams</th>\n";
					}
					if($(this).find('tot_faults').text()!="") {
						content += "<th>Total<br>Faults</th>\n";
					}
					if($(this).find('ave_faults').text()!="") {
						content += "<th>Average<br>Faults</th>\n";
					}
					if($(this).find('winner').text()!="") {
						content += "<th>Winning<br>Team</th>\n";
					}
					if($(this).find('margin').text()!="") {
						content += "<th>Victory<br>Margin</th>\n";
					}
					if($(this).find('id').text()!="") {
						content += "<th>Link to<br>Results</th>\n";
					}
					content += "<tr>\n";
				});
				
				
				$(this).find('season').each(function (){ 
					content += "<tr>\n";
					if($(this).find('year')) {
						content += "<td class=\"center\">"+$(this).find('year').text()+"</td>\n";
					}
					if($(this).find('location').text()!="") {
						content += "<td>"+$(this).find('location').text()+"</td>\n";
					}
					if($(this).find('no_of_teams').text()!="") {
						content += "<td class=\"right\">"+$(this).find('no_of_teams').text()+"</td>\n";
					}
					if($(this).find('tot_faults').text()!="") {
						content += "<td class=\"right\">"+$(this).find('tot_faults').text()+"</td>\n";
					}
					if($(this).find('ave_faults').text()!="") {
						content += "<td class=\"right\">"+$(this).find('ave_faults').text()+"</td>\n";
					}
					if($(this).find('winner').text()!="") {
						content += "<td>"+$(this).find('winner').text()+"</td>\n";
					}
					if($(this).find('margin').text()!="") {
						content += "<td class=\"right\">"+$(this).find('margin').text()+"</td>\n";
					}
					if($(this).find('id').text()!="") {
						content += "<td><a href=\"drl_results.php?event_id="+$(this).find('id').text()+"\">Results</a></td>\n";
					}
					content += "</tr>\n";
				});
				content += "</table>\n";
			// display them
				$('#search_results_here').append(content);
			});
		}
		$('#search_results_here').html("<p>Loading please wait...</p>");

		// submit the details to the drl_team.php page
		var data = 'comp_search='+$('#comp_search').val();
		msie_post('archive_ajax_queries.php', data, post_process);	

		return false;
	});
}

function form_control() {
	$('#tg').change(function(){
		tg_change();
	});
	
	$('#toggle').click(function(){
		$('#stat_options').toggle();
		return false;
	});
	
	$('#range1').click(function () {
		$('#start').attr('disabled', '');
		$('#start').val(first_year);
		$('#end').attr('disabled', '');
		$('#end').val(year_start);
	});
	
	$('#range0').click(function () {
		$('#start').attr('disabled', 'disabled');
		$('#start').val('');
		$('#end').attr('disabled', 'disabled');
		$('#end').val('');
	});
	
	$('#clear').click (function () {
		for(var i=0; i<8; i++) {
			$('#adv'+i).attr('checked', '');
		}
		return false;
	});
	
	$('#selectall').click (function () {
		for(var i=0; i<8; i++) {
			$('#adv'+i).attr('checked', 'checked');
		}
		return false;
	});
}

function queries() {
	function post_process (data) {
		var year_limit = $(data).find('year_limit').text();
		var i=0;
		var columns = new Array(parseInt($(data).find('columns').text()));
		var table_id = "#stat"+$(data).find('id').text()+"tab";
		var content = "<tr>\n";
		$(data).find('name').each(function() {
			content += "<th>"+$(this).text()+"</th>\n";
			columns[i] = $(this).text();
			i++; // sets i to equal the length of the array
		});
		content += "</tr>\n";
		$(data).find('row').each(function () {
			content += "<tr>\n";
			// first column
			switch (columns[0]) {
				case "Year":
					content += "<td class=\"center bold\">"+$(this).find('year').text()+"</td>\n";
					break;
				case "Ranking":
					content += "<td class=\"right bold\">"+$(this).find('rank').text()+"</td>\n";
					break;
				case "Points":
					content += "<td class=\"right bold\">"+$(this).find('points').text()+"</td>\n";
					break;
				case "Fault diff":
					content += "<td class=\"right bold\">"+$(this).find('fd').text()+"</td>\n";
					break;
				case "Entry":
					content += "<td class=\"center bold\">"+$(this).find('teams').text()+"</td>\n";
					break;
				case "Faults":
					content += "<td class=\"right bold\">"+$(this).find('faults').text()+"</td>\n";
					break;
				case "Margin":
					content += "<td class=\"right bold\">"+$(this).find('margin').text()+"</td>\n";
					break;
				case "Comps":
					content += "<td class=\"center bold\">"+$(this).find('comps').text()+"</td>\n";
					break;
				case "Ave.":
					content += "<td class=\"right bold\">"+$(this).find('faults').text()+"</td>\n";
					break;
				case "Wins":
					content += "<td class=\"center bold\">"+$(this).find('wins').text()+"</td>\n";
					break;
				case "Percentage":
					content += "<td class=\"right bold\">"+$(this).find('wins').text()+"</td>\n";
					break;
			}
			// second column
			switch (columns[1]) {
				case "5 or more competitions":
					if($(this).find('year').text() == year_limit) {
						content += "<td class=\"center bold\">"+$(this).find('five').text()+"</td>\n";
					} else {
						content += "<td class=\"center\">"+$(this).find('five').text()+"</td>\n";
					}
					break;
				case "Competitions":
					if($(this).find('year').text() == year_limit) {
						content += "<td class=\"center bold\">"+$(this).find('comps').text()+"</td>\n";
					} else {
						content += "<td class=\"center\">"+$(this).find('comps').text()+"</td>\n";
					}
					break;
				case "Team and year":
					if($(this).find('year').text() == year_limit) {
						content += "<td class=\"bold\">"+$(this).find('team').text()+", "+$(this).find('year').text()+"</td>\n";
					} else {
						content += "<td>"+$(this).find('team').text()+", "+$(this).find('year').text()+"</td>\n";
					}
					break;
				case "Competition":
					if($(this).find('year').text() == year_limit) {
						content += "<td class=\"bold\">"+$(this).find('comp').text()+", "+$(this).find('year').text()+"</td>\n";
					} else {
						content += "<td>"+$(this).find('comp').text()+", "+$(this).find('year').text()+"</td>\n";
					}
					break;
				case "Team":
					if($(this).find('year').text() == year_limit || $(this).find('last').text() == year_limit) {
						content += "<td class=\"bold\">"+$(this).find('team').text()+"</td>\n";
					} else {
						content += "<td>"+$(this).find('team').text()+"</td>\n";
					}
					break;
				case "Ringer":
					content += "<td>"+$(this).find('ringer').text()+"</td>\n";
					break; 
			}
			if(i>2) {
				// third column
				switch (columns[2]) {
					case "All-comers":
						if($(this).find('year').text() == year_end) {
							content += "<td class=\"center bold\">"+$(this).find('ac').text()+"</td>\n";
						} else {
							content += "<td class=\"center\">"+$(this).find('ac').text()+"</td>\n";
						}
						break;
					case "Winners and Runners Up":
						content += "<td class=\"italic\">"+$(this).find('teams').text()+"</td>\n";
						break;
					case "Years active":
						content += "<td class=\"center\">"+$(this).find('first').text()+" - "+$(this).find('last').text()+"</td>\n";
						break;
					case "Competition":
						content += "<td class=\"italic\">"+$(this).find('comp').text()+", "+$(this).find('year').text()+"</td>\n";
						break;
					case "Comps":
						content += "<td class=\"right\">"+$(this).find('comps').text()+"</td>\n";
						break;
					case "Entry":
						content += "<td class=\"right\">"+$(this).find('teams').text()+"</td>\n";
						break;
				}
				if(i>3) {
					// fourth column - only one possible header
					content += "<td class=\"right\">"+$(this).find('comps').text()+"</td>\n";
				}
			}
			content += "</tr>\n";
		});
		$(table_id).html(content);
	}
	
	function send_post (stat) {
	msie_post('stat_ajax_queries.php', 'stat_no='+stat, post_process);
	}
/*	$('#stat01').click(function(){
		send_post(1);
	});*/
	$('#stat03').click(function(){
		send_post(3);
	});
	$('#stat04').click(function(){
		send_post(4);
	});
	$('#stat05').click(function(){
		send_post(5);
	});
	$('#stat06').click(function(){
		send_post(6);
	});
	$('#stat08').click(function(){
		send_post(8);
	});
	$('#stat09').click(function(){
		send_post(9);
	});
	$('#stat10').click(function(){
		send_post(10);
	});
	$('#stat11').click(function(){
		send_post(11);
	});
	$('#stat12').click(function(){
		send_post(12);
	});
	$('#stat13').click(function(){
		send_post(13);
	});
	$('#stat14').click(function(){
		send_post(14);
	});
	$('#stat15').click(function(){
		send_post(15);
	});
	$('#stat16').click(function(){
		send_post(16);
	});
	$('#stat17').click(function(){
		send_post(17);
	});
	$('#stat18').click(function(){
		send_post(18);
	});
	$('#stat19').click(function(){
		send_post(19);
	});
	$('#stat20').click(function(){
		send_post(20);
	});
	$('#stat21').click(function(){
		send_post(21);
	});
	$('#stat22').click(function(){
		send_post(22);
	});
	$('#stat23').click(function(){
		send_post(23);
	});
	$('#stat24').click(function(){
		send_post(24);
	});
	$('#stat25').click(function(){
		send_post(25);
	});
	$('#stat26').click(function(){
		send_post(26);
	});
	$('#stat27').click(function(){
		send_post(27);
	});
	$('#stat28').click(function(){
		send_post(28);
	});
	$('#stat29').click(function(){
		send_post(29);
	});
	$('#stat31').click(function(){
		send_post(31);
	});
	$('#stat32').click(function(){
		send_post(32);
	});
	$('#stat33').click(function(){
		send_post(33);
	});
	$('#stat34').click(function(){
		send_post(34);
	});
	$('#stat35').click(function(){
		send_post(35);
	});
	$('#stat36').click(function(){
		send_post(36);
	});
	$('#stat37').click(function(){
		send_post(37);
	});
	$('#stat38').click(function(){
		send_post(38);
	});
	$('#stat40').click(function(){
		send_post(40);
	});
	$('#stat41').click(function(){
		send_post(41);
	});
	$('#stat42').click(function(){
		send_post(42);
	});
	$('#stat43').click(function(){
		send_post(43);
	});
	$('#stat44').click(function(){
		send_post(44);
	});

}
