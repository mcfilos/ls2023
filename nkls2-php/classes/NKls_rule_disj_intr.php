<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_disj_intr extends NKls_rule{
	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
	 	$lines_examined = 0;
		static $line1_min = -1;
		$strings_here = array();
		
		global $start;
		$startrun = microtime(true);
		$newstructures = array();
		
		static $newsubformulas;
		
		//extends original propositional subformulas 
		if(!is_array($newsubformulas)){
			$newsubformulas = array();
			foreach($subformulas as $string=>$subformula){
				if($subformula->op() == 'V'){
					$props = array(
								$subformula->subformula1(),
								$subformula->subformula2(),
								$subformula
							);
					foreach($props as $pk=> $prop){
						if($freevars = $prop->variables_free_flat(false)){
							$newsubformulas =  array_merge($newsubformulas,$prop->mass_clone(true));
						}
						else{
							$newsubformulas[$prop->string()] = $prop;
						}						
					}
				}
			}
		}

		foreach($lines as $l=>$line){
			$linestr = $line->line_formula();
			if(!isset($newsubformulas[$line->line_formula()])) continue;

			if($line1_min >= $l) continue;
			$line1_min = $l;
			
			$newforms = $nextstrings = array();
			foreach($newsubformulas as $str=>$newsubformula){
				if($newsubformula->op() == 'V' && ($newsubformula->subformula1()->string() == $linestr || $newsubformula->subformula2()->string() == $linestr)){
					$newforms[] = $newsubformula;
					$nextstrings[] = $newsubformula->string();
				}
			}			
			
			$bls = $line->base_lines();
			$sls = array($line->line_number());
			
			foreach($newforms as $kf=>$newform){
				$nextstring = $nextstrings[$kf];
				$nextstringcheck = $nextstrings[$kf].'-'.implode(',',$bls);				
			
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
			$lines_examined++;
		}
	
		$this->system->log('IV'.count($newstructures).'(lex '.($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}

?>