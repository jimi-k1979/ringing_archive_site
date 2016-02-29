<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
?>
<title>James Kerslake's Devon-style bellringing site - Ladder archive</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/ladder_banner.inc"); ?>

<!-- Sub menu -->
<div id='sub_menu_banner'>
<div id='sub_menu_container' style='width:545px;'>
<div id='sub_unselected'><a class='sub_unselect' href='index.html'>Ladder Home</a></div>
<div id='sub_split'></div>
<div id='sub_selected'><a class='sub_unselect' href='current_fixtures.html'>Fixtures &amp; Results</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='lad_tables.php?season=2009b'>Tables</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_select' href='lad_archive.php'>Archive</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='#'>Statistics</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='howitworks.html'>How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
	<div class="head_2">Ringing Ladder archive</div>
	<div class='text'>Hopefully the drop down menus below will take you to the season and section of your choice - you need to select both a season and a section otherwise it won't work!</div>
	<div class='head_4'>Results archive</div>
	<div style='margin: 5px; text-align:center'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");

	// Get the data from table for first list box
	$result=mysqli_query($cxn, "SELECT season_id, description FROM ladder_seasons ORDER BY season_id"); 

	// form start
	echo "<form method='get' action='lad_results.php'>\n";

	// First drop-down list
	echo "	<select name='season' id='season'>
		<option value=''>Select a season</option>\n";
	while($lad_seas = mysqli_fetch_assoc($result)) { 
		echo  "		<option value='$lad_seas[season_id]'>$lad_seas[description]</option>\n";
	}
	echo "	</select>\n";

	// Get the data for the second list box
	$result=mysqli_query($cxn, "SELECT section_id, description FROM ladder_sections ORDER BY section_id");
	// Second drop-down list
	echo "	<select name='section' id='section'>
		<option value=''>Select a section</option>\n";
	while($lad_sect = mysqli_fetch_assoc($result)) {
		echo "		<option value='$lad_sect[section_id]'>$lad_sect[section_id] - $lad_sect[description]</option>\n";
	}
	echo "	</select>\n";

	echo "	<input type='submit' value='Go!'>\n";
	echo "</form>\n";
?>
	</div>

	<div class='head_4'>Tables archive</div>
	<div style='margin: 5px; text-align:center'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");

	// Get the data from table for first list box
	$result=mysqli_query($cxn, "SELECT season_id, description FROM ladder_seasons ORDER BY season_id"); 

	// form start
	echo "<form method='get' action='lad_tables.php'>\n";

	// First drop-down list
	echo "	<select name='season' id='season'>
		<option value=''>Select a season</option>\n";
	while($lad_seas = mysqli_fetch_assoc($result)) { 
		echo  "		<option value='$lad_seas[season_id]'>$lad_seas[description]</option>\n";
	}
	echo "	</select>\n";

	// Get the data for the second list box
	$result=mysqli_query($cxn, "SELECT section_id, description FROM ladder_sections ORDER BY section_id");
	// Second drop-down list
	echo "	<select name='section' id='section'>
		<option value=''>Select a section</option>
		<option value=''>All sections</option>\n";
	while($lad_sect = mysqli_fetch_assoc($result)) {
		echo "		<option value='$lad_sect[section_id]'>$lad_sect[section_id] - $lad_sect[description]</option>\n";
	}
	echo "	</select>\n";

	echo "	<input type='submit' value='Go!'>\n";
	echo "</form>\n";
?>
	</div>
</div>
</body>
</html>