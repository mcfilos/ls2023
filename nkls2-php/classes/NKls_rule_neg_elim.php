<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_neg_elim extends NKls_rule{
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		
		$lines_examined = array();
		static $line1_min = -1;
		static $line2_min = -1;
		$strings_here = array();
		
		global $start;
		$startrun = microtime(true);
		$newstructures = array();
		
		foreach($lines as $l=>$line){
			
			if($line->formula()->op() != '~') continue;
			 
			foreach($lines as $ll=>$line2){
				
				if(($line1_min >= $l && $line2_min >= $ll)) continue;
				$line1_min = $l;
				$line2_min = $ll;
				
				if($line2->formula()->op() == '~' || $line2->formula()->string() != $line->formula()->subformula1()->string()) continue;
				
				$ind = $l < $ll ? $l.'-'.$ll : $ll.'-'.$l;
				if(isset($lines_examined[$ind])) continue; 
				$lines_examined[$ind] = true; 
				$newform = new NKls_formula('λ');
				$nextstring = 'λ';
				
				$bls = array_unique(array_merge($line->base_lines(),$line2->base_lines()));
				$sls = array($line->line_number(), $line2->line_number());
				
				sort($bls);
				sort($sls);
				$nextstringcheck = $nextstring.'-'.implode(',',$bls);
				
				if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
					$newstructures[] = array(
								'formula' => $newform,
								'base_lines' => $bls,
								'source_lines' => $sls
							);	
					$strings_here[$nextstringcheck] = true;	
					
					$is_target = $nextstring == $this->system->target->string() && !array_diff($bls,$this->system->premises_base_lines());
					if($is_target){
						$newstructures = array(end($newstructures));
						break(2);
					}
					
				}
			}
		}
		$this->system->log('E~'.count($newstructures).'(lex '.count($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;
	}
}

?>