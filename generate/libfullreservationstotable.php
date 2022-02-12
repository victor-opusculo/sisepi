<?php
require_once("checkLogin.php");
require_once("../includes/common.php");
require_once("../model/database/libraryreservations.database.php");

$fullReservationsDataRows = getFullReservationsList(($_GET["orderBy"] ?? ""), ($_GET["q"] ?? ""));
if (!$fullReservationsDataRows) die("Não há dados de acordo com o critério atual de pesquisa.");

$itemsCount = count($fullReservationsDataRows);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Reservas da biblioteca</title>
	<link rel="shortcut icon" type="image/x-icon" href="../pics/favicon.ico">
	<style>
		table { border-collapse: collapse; }
		td, th { border: 1px solid darkgray; }
		th { background-color: lightgray; }
	</style>
</head>
	<body>
		<p><?php echo $itemsCount; ?> itens cadastrados</p>
		<table>
			<thead>
				<tr>
					<?php foreach ($fullReservationsDataRows[0] as $h => $v): ?>
					<th><?php echo $h; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($fullReservationsDataRows as $dr): ?>
				<tr>
					<?php foreach ($dr as $col => $val): ?>
					<td><?php echo hsc($val); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>