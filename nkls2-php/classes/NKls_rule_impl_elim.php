<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_impl_elim extends NKls_rule{
	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		$newstructures = array();
		global $start;
		$startrun = microtime(true);
		
		$lines_examined = 0;
		static $line1_min = -1;
		static $line2_min = -1;
		$strings_here = array();
		
		//get max length
		foreach($lines as $l=>$line){
			if($line->formula()->op() != '->') continue;
			
			foreach($lines as $ll=>$line2){
				if($line1_min >= $l && $line2_min >= $ll) continue;
				$line1_min = $l;
				$line2_min = $ll;
				
				if($line2->formula()->string() == $line->formula()->subformula1()->string()){		
					$bls = array_unique(array_merge($line->base_lines(),$line2->base_lines()));
					sort($bls);
					$sl  = array($line->line_number(), $line2->line_number());
					sort($sl);
					$nextstring = $line->formula()->subformula2()->string();
					$nextstringcheck = $nextstring.'-'.implode(',',$bls);
			
		
					if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){	
						$newstructures[] = array(
									'formula' => $line->formula()->subformula2(),
									'base_lines' => $bls,
									'source_lines' => $sl
								);		
						$strings_here[$nextstringcheck] = true;	
					
						$is_target = $nextstring == $this->system->target->string() && !array_diff($bls,$this->system->premises_base_lines());
						if($is_target){
							$newstructures = array(end($newstructures));
							break(2);
						}
					}
				}
				$lines_examined++;	
			}
		}
		
		$this->system->log('E->'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;		
	}
	
}

?>