<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
ini_set('memory_limit', '8048M');
set_time_limit(5500);
require 'classes/NKls.php';

$system = new NKls();
$dict = $system->dict()->values();
$dictfound = $parse_input= $parse_code = false;

if(!empty($_GET['lines'])){
	$parse_input = $_GET['lines'];
}
else{
	$dictfound = !empty($_GET['dk']) && isset($dict[$_GET['dk']]) ? $dict[$_GET['dk']] : false;
	$parse_input = $dictfound;	
}


list($parse_code,$parse_errors) =$system->dict()->parse($parse_input); 

//die;

$run_param = isset($_GET['as_use']) ? $_GET['as_use'] : false;  


$mode_test = false;


if($mode_test){

}
elseif($parse_code && !$parse_errors){
	$exclude_rules = $log = $exception = array();
	//var_dump($parse_code);
	try{
		eval($parse_code);
		$exclude_rules = array();
		$log = $system->run(true,$run_param);
	}
	catch(Exception $exception){
		
	}
}

global $start;

?>


<html>
	<head>
		<title>NKls Calculator</title>
		
	
	</head>
	<body>
		<div align="left-side"> <img src="https://images-na.ssl-images-amazon.com/images/I/51nIdZ1HWSL._SX352_BO1,204,203,200_.jpg" width="100" /><div>
		<a href="index.php">Home</a>
	
		<h1 style="color:green;">Lemmon-style NK Calculator</h1>
		<h5 > Work in progress. Faculty of Philosophy. University of Bucharest. Marian Calborean <a href="mailto:mc@filos.ro">mc@filos.ro</a></h5>
	
		
		<h3 style="color:green;">Examples from Forbes 1994</h3>
<?
	
$k=1;
foreach($dict as $dk=>$dic):	
?>		
	<?= $k++ ?>	<a <?= $dic == $dictfound ? ' style="font-weight: bold;"' : '' ?> href="index.php?dk=<?= $dk ?>"><?= $dk ?></a> &nbsp; &nbsp;
<?
endforeach;
?>

	<h3 style="color:green;">Insert premises (Use ->, <->, &, V and ~. For format click one example above) and click Send</h3>
<?

if(!empty($exception)){
	echo '<div style="color:red; padding: 20px">ERROR: '.$exception->getMessage().'</div>';
}
elseif($parse_input && !$parse_errors){
	echo '<div style="color:green; padding: 20px">SUCCES AT PARSING. SEE RESULT BELOW</div>';
}
elseif($parse_input){
	echo '<div style="color:red; padding: 20px">'.implode('<br />',$parse_errors).'</div>';
}

?>		
		
		
		
		<form name="compute" method="get" action="index.php">
			<textarea name="lines" style="width: 90%;" rows="10"><?=htmlentities($parse_input) ?></textarea>
			<br />Run variant: 
			<label><input type="radio" name="as_use" value="all" <?= !in_array($run_param,['nc','noas','dn','osf']) ? ' checked="checked"' : '' ?> /> Full</label>
			<label><input type="radio" name="as_use" value="noas" <?= in_array($run_param,['noas']) ? ' checked="checked"' : '' ?> /> No independent assumptions</label>
			<label><input type="radio" name="as_use" value="osf" <?= in_array($run_param,['osf']) ? ' checked="checked"' : '' ?> /> Only premise subformula assumptions</label>
			<label><input type="radio" name="as_use" value="nc" <?= in_array($run_param,['nc']) ? ' checked="checked"' : '' ?>/> Assume only the negation of the conclusion</label>
			<label><input type="radio" name="as_use" value="dn" <?= in_array($run_param,['dn']) ? ' checked="checked"' : '' ?> /> Run DN after each rule</label>
				
			
			
		
		<br />
		<label><input type="checkbox" name="see" value="1" <?= !empty($_GET['see']) ? ' checked="checked"' : '' ?> /> See log</label>
		<br />
		
		<br />	<input type="submit" value="Send" />
		</form>
<?
if(!empty($log)){
	echo '<strong>'.json_encode(array_slice($log,-1)).'</strong>';
}
if(!empty($_GET['see']) && !empty($log)): 
?>		
	<pre style="color: red;">
	<?= nl2br(var_export($log,true)) ?>
	</pre>
		
		
<?
endif;
if($parse_input):
?>		
		<br /><br />		

		<h2>Exercise (<?= $system->lines_success ? '<span style="color: green">Success!</span>' : '<span style="color: red">No success</span>'; ?>)</h2>
<?
foreach($system->lines_premises() as $k=>$premise){
	$m = $k < count($system->lines_premises()) - 1 ? ', ' : '';
	echo $premise->line_formula().$m;
}
echo $system->target ? '&nbsp;|-&nbsp;'.$system->target->string() : '';
?>		
		
		
		<h2>All lines (<?= count($system->lines) ?>)</h2>
				
	
		<table width="700">
			<tr style="font-weight: bold;">
				<td>Base lines</th>
				<td>No</th>
				<td>Formula</th>
				<td>Source type & lines</th>
				<td>Pass</th>
			</tr>

<?
	$lines = count($system->lines) < 20000 ? $system->lines : array_merge(array_slice($system->lines,0,10000), array_slice($system->lines,-10000));
	foreach($lines as $line):
?>			
			<tr>
				<td><?= $line->line_base_lines() ?></td>
				<td style="font-weight: bold;"><?= $line->line_number(true) ?></td>
				<td><?= $line->line_formula() ?></td>
				<td><?= $line->line_source_info() ?></td>
				<td><?= $line->line_pass_info() ?></td>
			</tr>
<?
	endforeach;
?>		
		
		</table>
		<br /><br />
The target formula is: <strong><?= $system->target->string() ?: '-' ?></strong>		
	
	
	
	<br /><br /><br />
	
<?
	if(!$system->lines_success):
		echo '<h2 style="color: red">No success</h2>';
	else:
?>	
		<h2>First path to success</h2>
				
	
		<table width="700">
			<tr style="font-weight: bold;">
				<td>Base lines</th>
				<td>No</th>
				<td>Formula</th>
				<td>Source type & lines</th>
			</tr>

<?
		foreach($system->lines_success as $k=>$line):
?>			
			<tr>
				<td><?= $line->line_base_lines() ?></td>
				<td style="font-weight: bold;"><?= $line->line_number(true) ?></td>
				<td><?= $line->line_formula() ?></td>
				<td><?= $line->line_source_info() ?></td>
			</tr>
<?
		endforeach;
?>			
			
			
			
			
		</table>
		
		<br /><br /><br />
		<h2>Renumbered</h2>
<?
foreach($system->lines_premises() as $k=>$premise){
	$m = $k < count($system->lines_premises()) - 1 ? ', ' : '';
	echo $premise->line_formula().$m;
}
echo $system->target ? '&nbsp;|-&nbsp;'.$system->target->string() : '';
?>	
		<table width="700">
			<tr style="font-weight: bold;">
				<td>Base lines</th>
				<td>No</th>
				<td>Formula</th>
				<td>Source type & lines</th>
			</tr>

<?
		foreach($system->lines_success(true) as $k=>$line):
?>			
			<tr>
				<td><?= $line->line_base_lines ?></td>
				<td style="font-weight: bold;"><?= $line->line_number ?></td>
				<td><?= $line->line_formula ?></td>
				<td><?= $line->line_source_info ?></td>
			</tr>
<?
		endforeach;
?>			
			
			
			
			
		</table>
		
					
<?
	endif;
endif;
?>	
	
	</body>
</html>