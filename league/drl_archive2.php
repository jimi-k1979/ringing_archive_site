<!DOCTYPE html>
<?php
include ("../allpages/variables.php");
?>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL Archive</title>
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new2.css">
<link type="text/css" rel="stylesheet" href="/allpages/jquery-ui-1.8.6.custom.css">

<script type="text/javascript">
function drop_down_year_set (decade, selector) {
	// when more years are added to the database these two variables need to change
	var start_year = <?php echo $earliest_year; ?>;
	var end_year = <?php echo $latest_year; ?>;
	var i
		
	$(selector).html('<option value="">Select a year</option>\n');
	
	if(decade<=start_year) {
		for(i=start_year; i<decade+10; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	} else if(end_year<decade+10) {
		for(i=decade; i<=end_year; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	} else {
		for(i=decade; i<decade+10; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	}
}

function comp_check(comp) {
	if(comp=='Newton Abbot, Ipplepen and Torbay Deaneries') {
		comp = 'NAIT Deaneries';
	}
	return comp;
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
			break; // shouldn't gert here!
	}
}

$(document).ready(function() {
// general display methods
	$('#tabs').tabs({
//		selected:1
	});
	
	$('#result_accord').accordion({
		header: 'h3',
//		active: false,
		collapsible: true,
		autoHeight: false
	});

// results_year form methods
	$('#decade_ry').change(function () {
		if($(this).val()!='') {
			drop_down_year_set(parseInt($(this).val()),'#year_ry');
			$('#comp_ry').html('<option value="">Select a year first</option>\n');
		} else {
			$('#year_ry').html('<option value="">Select a decade first</option>\n');
			$('#comp_ry').html('<option value="">Select a decade first</option>\n');
		}
	});	

	$('#year_ry').change(function () {
		function post_process(data) {
			$(data).find('event').each( function (i) {
				$('#comp_ry').append('<option value="'+$(this).find('id').text()+'">'+comp_check($(this).find('comp').text())+'</option>\n');
			});
		}		
		if($(this).val()=='') {
			$('#comp_ry').html('<option value="">Select a year first</option>\n');
		} else {
			$('#comp_ry').html('<option value="0">Select a competition</option>\n');
			var form_data=$('#results_year').serialize();
			if($.browser.msie) {
				$.ajax({
					type: "POST",
					url: "archive_ajax_queries.php",  // ! watch out for same
					dataType: "xml", // 'xml' passes it through the browser's xml parser
					data: form_data,
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
				$.post('archive_ajax_queries.php', form_data, post_process);
			}
		}
	});
	
	$('#results_year').submit(function () {
		function post_process(data) {
			// table header
			$('#results_here').html('<h3>'+$(data).find('year').text()+' '+$(data).find('comp').text()+'<br>held at '+$(data).find('loc').text()+'</h3>\n');
			var content='<table style="margin:10px auto"><tr><th>&nbsp;</th><th style="min-width:200px">Team</th><th>Faults</th></tr>';
			$(data).find('result').each( function (i) {
				content+='<tr>\n<td style="text-align:right">'+$(this).find('pos').text()+'</td>\n';
				content+='<td>'+$(this).find('team').text()+'</td>\n';
				content+='<td style="text-align:right">'+$(this).find('faults').text()+'</td>\n</tr>\n';
			});
			$('#results_here').append(content);
		}
		var form_data=$(this).serialize();
		if($.browser.msie) {
			$.ajax({
				type: "POST",
				url: "archive_ajax_queries.php",  // ! watch out for same
				dataType: "xml", // 'xml' passes it through the browser's xml parser
				data: form_data,
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
			$.post('archive_ajax_queries.php', form_data, post_process);
		}
		return false;
	});

// results_comp form methods
	$('#comp_rc').change(function () {
		function post_process(data) {
			$(data).find('event').each( function (i) {
				$('#year_rc').append('<option value="'+$(this).find('id').text()+'">'+$(this).find('year').text()+'</option>\n');
			});
		}
		if($(this).val()=='') {
			$('#year_rc').html('<option value="">Select a competition first</option>\n');
		} else {
			$('#year_rc').html('<option value="0">Select a year</option>\n');
			var form_data=$('#results_comp').serialize();
			if($.browser.msie) {
				$.ajax({
					type: "POST",
					url: "archive_ajax_queries.php",  // ! watch out for same
					dataType: "xml", // 'xml' passes it through the browser's xml parser
					data: form_data,
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
				$.post('archive_ajax_queries.php', form_data, post_process);
			}
		}
	});


	$('#results_comp').submit(function () {
		function post_process(data) {
			// table header
			$('#results_here').html('<h3>'+$(data).find('year').text()+' '+$(data).find('comp').text()+'<br>held at '+$(data).find('loc').text()+'</h3>\n');
			var content='<table style="margin:10px auto"><tr><th>&nbsp;</th><th style="min-width:200px">Team</th><th>Faults</th></tr>';
			$(data).find('result').each( function (i) {
				content+='<tr>\n<td style="text-align:right">'+$(this).find('pos').text()+'</td>\n';
				content+='<td>'+$(this).find('team').text()+'</td>\n';
				content+='<td style="text-align:right">'+$(this).find('faults').text()+'</td>\n</tr>\n';
			});
			$('#results_here').append(content);
		}
		var form_data=$(this).serialize();
		if($.browser.msie) {
			$.ajax({
				type: "POST",
				url: "archive_ajax_queries.php",  // ! watch out for same
				dataType: "xml", // 'xml' passes it through the browser's xml parser
				data: form_data,
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
			$.post('archive_ajax_queries.php', form_data, post_process);
		}
		return false;
	});
	
// results_location form methods
	$('#location_rl').change(function () {
		function post_process(data) {
			$(data).find('comp').each( function (i) {
				$('#comp_rl').append('<option value="'+$(this).find('name').text()+'">'+comp_check($(this).find('name').text())+'</option>\n');
			});
		}
		if($(this).val()=='') {
			$('#comp_rl').html('<option value="">Select a location first</option>\n');
			$('#year_rl').html('<option value="">Select a location first</option>\n');
		} else {
			$('#comp_rl').html('<option value="0">Select a competition</option>\n');
			$('#year_rl').html('<option value="">Select a competition first</option>\n');
			var form_data=$('#results_loc').serialize();
			if($.browser.msie) {
				$.ajax({
					type: "POST",
					url: "archive_ajax_queries.php",  // ! watch out for same
					dataType: "xml", // 'xml' passes it through the browser's xml parser
					data: form_data,
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
				$.post('archive_ajax_queries.php', form_data, post_process);
			}
		}
	});

	$('#comp_rl').change(function () {
		function post_process(data) {
			$(data).find('event').each( function (i) {
				$('#year_rl').append('<option value="'+$(this).find('id').text()+'">'+$(this).find('year').text()+'</option>\n');
			});
		}
		if($(this).val()=='0') {
			$('#year_rl').html('<option value="">Select a competition first</option>\n');
		} else {
			$('#year_rl').html('<option value="0">Select a year</option>\n');
			var form_data=$('#results_loc').serialize();
			if($.browser.msie) {
				$.ajax({
					type: "POST",
					url: "archive_ajax_queries.php",  // ! watch out for same
					dataType: "xml", // 'xml' passes it through the browser's xml parser
					data: form_data,
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
				$.post('archive_ajax_queries.php', form_data, post_process);
			}
		}
	});

	$('#results_loc').submit(function () {
		function post_process(data) {
			// table header
			$('#results_here').html('<h3>'+$(data).find('year').text()+' '+$(data).find('comp').text()+'<br>held at '+$(data).find('loc').text()+'</h3>\n');
			var content='<table style="margin:10px auto"><tr><th>&nbsp;</th><th style="min-width:200px">Team</th><th>Faults</th></tr>\n';
			$(data).find('result').each( function (i) {
				content+='<tr>\n<td style="text-align:right">'+$(this).find('pos').text()+'</td>\n';
				content+='<td>'+$(this).find('team').text()+'</td>\n';
				content+='<td style="text-align:right">'+$(this).find('faults').text()+'</td>\n</tr>\n';
			});
			content+='</table>\n';
			$('#results_here').append(content);
		}
		var form_data=$(this).serialize();
		if($.browser.msie) {
			$.ajax({
				type: "POST",
				url: "archive_ajax_queries.php",  // ! watch out for same
				dataType: "xml", // 'xml' passes it through the browser's xml parser
				data: form_data,
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
			$.post('archive_ajax_queries.php', form_data, post_process);
		}
		return false;
	});

// ringers form methods
	$('#comp_2').change(function () {
		function post_process(data) {
			$(data).find('event').each( function (i) {
				$('#year_2').append('<option value="'+$(this).find('id').text()+'">'+$(this).find('year').text()+'</option>\n');
			});
		}
		if($(this).val()=='') {
			$('#year_2').html('<option value="">Select a competition first</option>\n');
		} else {
			$('#year_2').html('<option value="0">Select a year</option>\n');
			var form_data = $('#ringers').serialize();
			if($.browser.msie) {
				$.ajax({
					type: "POST",
					url: "archive_ajax_queries.php",  // ! watch out for same
					dataType: "xml", // 'xml' passes it through the browser's xml parser
					data: form_data,
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
				$.post('archive_ajax_queries.php', form_data, post_process);
			}
		}
	});
	
	$('#ringers').submit(function () {
		function post_process(data) {
			$('#team_here').html('<h3>'+$(data).find('year').text()+' '+$(data).find('comp').text()+'<br>held at '+$(data).find('loc').text()+'</h3>\n');
			if(!$(data).find('faults').text()) {
				$('#team_here').append('<p class="center"><span class="bold">Winning team: </span>'+$(data).find('team').text()+'</p>\n');
			} else {
				$('#team_here').append('<p class="center"><span class="bold">Winning team: </span>'+$(data).find('team').text()+'<br>'+$(data).find('faults').text()+' faults</p>\n');
			}
			var content='<table style="margin:10px auto"><tr><th>Bell</th><th style="min-width:200px">Ringer</th></tr>\n';
			$(data).find('ringer').each( function (i) {
				content+='<tr>\n<td style="text-align:center">'+$(this).find('bell').text()+'</td>\n';
				content+='<td>'+$(this).find('name').text()+'</td>\n</tr>\n';
			});
			content+='</table>\n';
			$('#team_here').append(content);
		}
		var form_data=$(this).serialize();
		if($.browser.msie) {
			$.ajax({
				type: "POST",
				url: "archive_ajax_queries.php",  // ! watch out for same
				dataType: "xml", // 'xml' passes it through the browser's xml parser
				data: form_data,
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
			$.post('archive_ajax_queries.php', form_data, post_process);
		}
		return false;
	});

// table_form methods	
	$('#decade_3').change(function () {
		if($(this).val()!='') {
			drop_down_year_set(parseInt($(this).val()),'#year_3');
		} else {
			$('#year_3').html('<option value="">Select a decade first</option>\n');
		}
	});	
	
	$('#table_form').submit(function () {
 // new version of the code starts here
		function post_process(data) {
			var content='<p class="small"><span class="bold">Competitions included:</span> ';
			$(data).find('event').each( function(i) {
				if($(this).find('comp').text()==$(data).find('comp').last().text()) {
					content+=loc_check($(this).find('comp').text(),$(this).find('loc').text());
				} else {
					content+=loc_check($(this).find('comp').text(),$(this).find('loc').text())+', ';
				}
			});
			content+='</p>';
			$('#table_here').html(content);
			content='<table style="margin:10px auto"><tr><th style="min-width:200px">Team</th><th>C</th><th style="min-width:65px">+ / -</th><th style="min-width:50px">Rank</th></tr>\n';
			$(data).find('placing').each( function(i) {
				content+='<tr>\n';
				content+='<td>'+$(this).find('team').text()+'</td>\n<td class="align-right">'+$(this).find('comps').text()+'</td>\n';
				content+='<td class="align-right">'+$(this).find('fd').text()+'</td>\n<td class="align-right bold">'+parseFloat($(this).find('rank').text()).toFixed(2)+'</td>\n';
				content+='</tr>\n';
			});
			content+='</table>\n';
			$('#table_here').append(content);
		}
		var form_data=$(this).serialize();
		if($.browser.msie) {
			$.ajax({
				type: "POST",
				url: "archive_ajax_queries.php",  // ! watch out for same
				dataType: "xml", // 'xml' passes it through the browser's xml parser
				data: form_data,
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
			$.post('archive_ajax_queries.php', form_data, post_process);
		}
		return false;
	});
});
</script>
<?php
	include("../allpages/dbinfo.inc");
	$cxn = mysqli_connect($host, $user, $passwd, $database);
?>
</head>

<body>

<!-- Banner at page top -->
<div id="top_banner">
<div id="top_left"></div>
<div id="top_center" class="head_1">Call change ringing resources</div>
<div id="top_right"></div>
</div>

<!-- Menu Banner -->
<div id="menu_banner">
<div id="menu_container">
<div id="ul_end"></div>
<div id="unselected_tab"><a class="unselected" href="/index.php">Home</a></div>
<div id="us_split"></div>
<div id="selected_tab"><a class="selected" href="/league/index.php">League</a></div>
<div id="su_split"></div>
<div id="unselected_tab"><a class="unselected" href="/ladder/index.html">Ladder</a></div>
<div id="uu_split"></div>
<div id="unselected_tab"><a class="unselected" href="/compositions/index.html">Compositions</a></div>
<div id="ur_end"></div>
</div>
</div>

<!-- Sub menu Banner -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if(date("m")<=4) {
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".($latest_year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".$latest_year."'>Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<div style="background:white; padding-bottom:2px">
<h2>Ringing Archive</h2>
<p>This section contains access to the archive of results, league tables and the new winning teams archive. Currently the results archive has results going back to <?php echo $earliest_year; ?>, as does the tables archive, although before 1986 it is not as well resourced as after 1986. The teams archive is currently rather patchy, although things are improving. If you can help by giving either the results of past competitions or the names of the ringers in the <span class="bold">winning</span> teams at any of the seven competitions given in the the drop down menu, please email them to me at <a href="mailto:webamster@myconid.co.uk">the usual address</a>.<br>
Throughout the menus the abbreviation NAIT Deaneries has been used for the Newton Abbot, Ipplepen and Torbay Deaneries.</p>
<div id="tabs" style="margin:5px;">
	<ul>
    	<li><a href="#results">Results</a></li>
        <li><a href="#teams">Teams</a></li>
        <li><a href="#tables">Tables</a></li>
    </ul>


    <div id="results">
	    <p>You can search for a set of results by either year, competition or location by clicking on the header. When you click the Go! button the results should appear below the search boxes.</p>
		<div id="result_accord">
	        <h3>Search by year</h3>
    	    <form id="results_year" method="post">
        	<label class="bold">Decade:
            <select name="decade" id="decade_ry">
        		<option value="">Select a decade</option>
				<option value="1950">1950-59</option>
				<option value="1960">1960-69</option>
                <option value="1970">1970-79</option>
	            <option value="1980">1980-89</option>
    	        <option value="1990">1990-99</option>
        	    <option value="2000">2000-09</option>
            	<option value="2010">2010-19</option>
	        </select></label>
            <label class="bold">Year:
    	    <select name="year" id="year_ry">
        		<option value="">Select a decade first</option>
    	    </select></label>
			<label class="bold">Competition:
	        <select name="event" id="comp_ry">
        		<option value="">Select a decade first</option>
	        </select></label>
	        <input id="results_submit_ry" type="submit" value="Go!">
  	      </form>
          
          <h3>Search by competition</h3>
    	  <form id="results_comp" method="post">
        	<label class="bold">Competition:
            <select name="comp" id="comp_rc">
        		<option value="">Select a competition</option>
<?php
	$query = "SELECT * FROM DRL_competitions ORDER BY competition";
	$result = mysqli_query($cxn,$query);
	while($row=mysqli_fetch_array($result)) {
		if(strcmp($row[0],"Newton Abbot, Ipplepen and Torbay Deaneries")==0) {
			echo "<option value=\"$row[0]\">NAIT Deaneries</option>\n";
		} else {
			echo "<option value=\"$row[0]\">$row[0]</option>\n";
		}
	}
?>
	        </select></label>
            <label class="bold">Year:
            <select name="event" id="year_rc">
            	<option value="">Select a competition first</option>
            </select></label>
            <input id="results_submit_rc" type="submit" value="Go!">
          </form>
          
          <h3>Search by location</h3>
          <form id="results_loc" method="post">
          <label class="bold">Location:
          	<select name="location" id="location_rl">
            	<option value="">Select a location</option>
<?php
	$query = "SELECT DISTINCT location FROM DRL_events ORDER BY location";
	$result = mysqli_query($cxn,$query);
	while($row=mysqli_fetch_array($result)) {
		echo "<option value=\"$row[0]\">$row[0]</option>\n";
	}
?>
            </select></label>
            <label class="bold">Competition:
            <select name="comp" id="comp_rl">
            	<option value="">Select a location first</option>
            </select></label>
            <label class="bold">Year:
            <select name="event" id="year_rl">
            	<option value="">Select a location first</option>
            </select></label>
            <input id="results_submit_rl" type="submit" value="Go!">
          </form>
        </div>
		<div id="results_here">
		</div>
    </div>


    <div id="teams">
    	<p>The selection made below will give the names of the ringers who rang in the winning team on the occasion chosen and, where possible, the number of faults they were given.</p>
        <form id="ringers" method="post">
            <label class="bold">Competition:
        	<select name="comp" id="comp_2">
            	<option value="">Select a competition</option>
                <option value="8bell">Devon 8 Bell Final</option>
                <option value="maj">Major Final</option>
                <option value="min">Minor Final</option>
                <option value="qualn">North Devon Qualifier</option>
                <option value="quals">South Devon Qualifier</option>
                <option value="sdev8">South Devon 8 Bell</option>
                <option value="inter">South Devon Interdeanery</option>
            </select></label>
			<input type="hidden" value="ringer" name="ring_flag"></input>
            <label class="bold">Year:
        	<select name="year" id="year_2">
            	<option value="">Select a competition first</option>
            </select></label>
            <input type="submit" value="Go!">
        </form>
		<div id="team_here">
		</div>
    </div>
 
    <div id="tables">
	    <p>The league table for the chosen season will appear below along with the list of events that are included to make the table. You can view either the all comers table or the table for teams ringing 5 or more competitions in a year by checking the respective box.</p>
        <form id="table_form" action="drl_tables.php" method="get">
        	<label class="bold">Decade:
   	  		<select name="decade" id="decade_3">
            	<option value="" selected="selected">Select a decade</option>
                <option value="1950">1950-59</option>
				<option value="1960">1960-69</option>
				<option value="1970">1970-79</option>
	            <option value="1980">1980-89</option>
                <option value="1990">1990-99</option>
                <option value="2000">2000-09</option>
                <option value="2010">2010-19</option>
            </select></label>
            <label class="bold">Year:
      		<select name="season" id="year_3">
            	<option value="">Select a decade first</option>
            </select></label>
              <label><input type="radio" name="allcomers" value="0" id="allcomers_0" checked="checked">
                All comers</label>
              <label><input type="radio" name="allcomers" value="1" id="allcomers_1">
                5 or more competitions</label>
            <input id="table_submit" type="submit" value="Go!">
        </form>
        <div id="table_here">
        </div>
    </div>
</div>
</div>
</body>
</html>
