<?php
include("../allpages/dbinfo.inc");
$cxn = mysqli_connect($host, $user, $passwd, $database);

function competition_list($cxn) {
	$query = "SELECT * FROM DRL_competitions ORDER BY competition ASC";
	$result = mysqli_query($cxn, $query);
	$i=0;
	while($row=mysqli_fetch_assoc($result)) {
		switch($row['competition']) {
			case 'Chittlehampton 6 Bell':
				$competitions[$i]="Chittlehampton (6 and 8 bell)";
				$i++;
				break;
			case 'Kilkhampton 6 Bell':
				$competitions[$i]="Kilkhampton - November comp (6 and 8 bell)";
				$i++;
				break;
			case 'National 6 Bell':
				$competitions[$i]="National";
				$i++;
				break;
			case 'Stratton 6 Bell':
				$competitions[$i]="Stratton (6 and 8 bell)";
				$i++;
				break;
			case 'Tamar Valley Guild':
				$competitions[$i]="Tamar Valley Guild (all sections)";
				$i++;
				break;
			case 'Tamar Valley Knockout Final':
				$competitions[$i]="Tamar Valley Guild Winter Knockout Final";
				$i++;
				break;
			case 'Chittlehampton 8 Bell':
			case 'Kilkhampton 8 Bell':
			case 'Stratton 8 Bell':
			case 'National 8 Bell':
			case 'Tamar Valley Knockout Final - open':
			case 'Tamar Valley Guild 8 Bell':
			case 'Tamar Valley Guild Open':
				// do nothing
				break;
			default:
				$competitions[$i]=$row['competition'];
				$i++;
				break;
		}
	}
	$competitions[$i]="Burrington 3 Tower";
	$i++;
	$competitions[$i]="Cadbury Deanery Winter League";
	$i++;
	$competitions[$i]="Dartmouth, St Petrox";
	$i++;
	$competitions[$i]="Devon Association Novice";
	$i++;
	$competitions[$i]="Devon Ringers Council 10 Bell";
	$i++;
	$competitions[$i]="Kilkhampton - John Cornish Shield";
	$i++;
	$competitions[$i]="Mid Devon Winter League";
	$i++;
	$competitions[$i]="NAIT Deaneries Gilbert Shield";
	$i++;
	$competitions[$i]="Tamar Valley Guild Winter Knockout";
	$i++;
	$competitions[$i]="Totnes Deanery Winter League";
	$i++;
	$competitions[$i]="Wilf Edworthy";
	$i++;
	
	sort($competitions);
	return $competitions;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

	<form id="by_comp" action="php_functs.php" method="post">
		<select id="comp" name="comp">
			<option value="none" selected="selected">select competition</option>
		<?php
			$comps = competition_list($cxn);
			foreach ($comps as $val) {
				echo "<option>$val</option>";
			}
		?>
		</select>
		<select id="year" name="year">
			<option value="0" selected="selected">Select year</option>
		<?php
			for($i=1972; $i<=2012; $i++) {
				echo "<option>$i</option>";
			}
		?>
		<input type="submit" name="go" value="go" id="go">
	</form>
  </body>
</html>