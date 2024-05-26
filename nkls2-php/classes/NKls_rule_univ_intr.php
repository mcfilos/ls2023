<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_univ_intr extends NKls_rule{
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		
		global $start;
		$startrun = microtime(true);
		
		//get lines with constants
		$newstructures = array();
		$lines_examined = 0;
		static $line1_min = -1;
		
		$strings_here = array();
		
		foreach($lines as $l=>$line){
			if($line1_min >= $l) continue;
			$line1_min = $l;
			$constants = $line->formula()->constants_flat(false);

			if(!$constants) continue;

			$baseconsts = array();
			//aici ar tb sa verificam ca nu se bazeaza pe ceva cu constanta aia
			foreach($line->base_lines() as $l){
				$baseline = $this->system->lines[$l];
				$baseconsts = array_merge($baseconsts, $baseline->formula()->constants_flat(false));
			}
			
			$vars = $line->formula()->variables_flat(false);
			foreach(array_keys(NKls_formula::$variables_used) as $var){
				if(in_array($var, $vars)) continue;
				foreach($constants as $const){
					if(in_array($const, $baseconsts)) continue;

					$subclone = $line->formula()->clone_replaced([],[$const => $var]);
					//var_dump($subclone->variables(false), $line->formula()->string(),$var);
					$nextf = $subclone  ? new NKls_formula($subclone,'A'.$var) : false;

					if(!$nextf) continue;
					$nextstring = $nextf->string();
					$nextstringcheck = $nextstring.'-'.implode(',',$line->base_lines());
				
					if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
						$newstructures[] = array(
								'formula' => $nextf,
								'base_lines' => $line->base_lines(),
								'source_lines' => array($line->line_number())
							);	
						$strings_here[$nextstringcheck] = true;	
							
						$is_target = $nextstring == $this->system->target->string() && !array_diff($line->base_lines(),$this->system->premises_base_lines());
						if($is_target){
							$newstructures = array(end($newstructures));
							break;
						}
					}	
				}
			}
			
			$lines_examined++;
		}
		$this->system->log('I∀'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;
	}
}
?>