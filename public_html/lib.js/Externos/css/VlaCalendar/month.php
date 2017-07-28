<?php
	include('vars.php');
	
	include('../../../../lib.conf/includes.php');
	
	importar("Utils.Dados.DataHora");
	importar("Site.Listas.ListaEventos");
	
	$lE = new ListaEventos;
	
	$start_monday	= $_POST['startMonday'] ? true : false;
	$picker			= $_POST['picker'] ? true : false;	
	
	if(isset($_POST['pickedDate']) && $_POST['pickedDate'] != 'null') {
		$pickedDateAr = explode('/', $_POST['pickedDate']);
		$pickedDate	  = @mktime(1, 1, 1, $pickedDateAr[1], $pickedDateAr[0], $pickedDateAr[2]);
	} else {
		$pickedDate   = mktime(1, 1, 1, date('n'), date('j'), date('Y'));
	}
	
	if(isset($_POST['gotoPickedDate'])) $ts = isset($_POST['ts']) ? $_POST['ts'] : mktime(1, 1, 1, date('n', $pickedDate), 1, date('Y', $pickedDate));
	else $ts = isset($_POST['ts']) ? $_POST['ts'] : mktime(1, 1, 1, date('n'), 1, date('Y'));
	
	$ts_year_full	= date('Y', $ts);
	$ts_year		= date('Y', $ts);
	$ts_month_nr	= date('n', $ts);
	$ts_month		= $month_labels[date('n', $ts)-1];
	$ts_nrodays		= date('t', $ts);
	
	$pr_ts			= mktime(1, 1, 1, $ts_month_nr-1, 1, $ts_year);
	$nx_ts			= mktime(1, 1, 1, $ts_month_nr+1, 1, $ts_year);
	
	$wdays_counter 	= date('w', $ts) - ($start_monday ? 1 : 0);
	if($wdays_counter == -1) $wdays_counter = 6;
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<table class="month<?php echo ($picker ? ' picker' : ''); ?>" cellpadding="0" summary="{'ts': '<?php echo $ts; ?>', 'pr_ts': '<?php echo $pr_ts; ?>', 'nx_ts': '<?php echo $nx_ts; ?>', 'label': '<?php echo $ts_month.', '.$ts_year_full; ?>', 
	'current': 'month', 'parent': 'year'<?php echo ($ts_year == 1979 && date('n', $ts) == 1 ? ", 'hide_left_arrow': '1'" : '').($ts_year == 2030 && date('n', $ts) == 12 ? ", 'hide_right_arrow': '1'" : ''); ?>}">
	<tr style="background-color: #FFF">
		<?php if(!$start_monday) echo '<th>'.$wdays_labels[6]."</th>\n"; ?>
		<th><?php echo $wdays_labels[0]; ?></th>
		<th><?php echo $wdays_labels[1]; ?></th>
		<th><?php echo $wdays_labels[2]; ?></th>
		<th><?php echo $wdays_labels[3]; ?></th>
		<th><?php echo $wdays_labels[4]; ?></th>
		<th><?php echo $wdays_labels[5]; ?></th>
		<?php if($start_monday) echo '<th>'.$wdays_labels[6]."</th>\n"; ?>
	</tr>
	<tr class="firstRow"><?php

	//Add days for the beginning non-month days
	for($i = 0; $i < $wdays_counter; $i++) {
		$day = date("t", $pr_ts)-($wdays_counter-$i)+1;
		$i_ts = mktime(1, 1, 1, $ts_month_nr-1, $day, $ts_year);
		
		echo '<td class="outsideDay" date="'."{'day': '".$day."', 'month': '".date('n', $i_ts)."', 'year': '".date('Y', $i_ts)."'}".'">'.$day.'</td>';
	}

	//Add month days
	$row = 0;
	for($i = 1; $i <= $ts_nrodays; $i++) {
		$i_ts = mktime(1, 1, 1, $ts_month_nr, $i, $ts_year);
		
		$mes = strlen($ts_month_nr) == 1 ? "0".$ts_month_nr : $ts_month_nr;
		$dia = strlen($i) == 1 ? "0".$i : $i;
		
		$d = new DataHora($ts_year.$mes.$dia);
		
		$lE->condicoes('', $d->mostrar("Ymd"), 'data_evento');
		$stY = '';
		if($lE->getTotal() > 0)
			$stY = 'style="color: #FFF;"';
		
		echo '<td'.($i_ts == $pickedDate ? ' class="selected"' : '').' '."date=\"{'day': '".$i."', 'month': '".$ts_month_nr."', 'year': '".$ts_year."'}\">";
		
		if($lE->getTotal() > 0)
			echo '<a href="./?p=agenda" '.$stY.'>'.$i.'</a>';
		else
			echo $i;
		
		echo '</td>';
		
		if($wdays_counter == 6 && ($i - 1) != $ts_nrodays) {
			$week_num = date("W", $i_ts) + 1;
			echo "</tr>\n\t<tr>";
			$wdays_counter = -1;
			$row++;
		}
		$wdays_counter++;
	}

	//Add outside days
	$a = 1;
	if($wdays_counter != 0) {
		for($i = $wdays_counter; $i < 7; $i++) {
			$i_ts = mktime(1, 1, 1, $ts_month_nr+1, $a, $ts_year);
			echo '<td class="outsideDay" date="'."{'day': '".$a."', 'month': '".date('n', $i_ts)."', 'year': '".date('Y', $i_ts)."'}".'">'.$a.'</td>';
			$a++;
		}
		$row++;
	}

	//Always have 6 rows
	if($row == 4 || $row == 5) {
		if($wdays_counter != 0) echo "</tr>\n\t<tr>";
		for($i = 0; $i < ($row == 5 ? 7 : 14); $i++) {
			$i_ts = mktime(1, 1, 1, $ts_month_nr+1, $a, $ts_year);
			echo '<td class="outsideDay" date="'."{'day': '".$a."', 'month': '".date('n', $i_ts)."', 'year': '".date('Y', $i_ts)."'}".'">'.$a.'</td>';
			$a++;
			if($i == 6) echo "</tr>\n\t<tr>";
		}
	}	

?></tr>
</table>