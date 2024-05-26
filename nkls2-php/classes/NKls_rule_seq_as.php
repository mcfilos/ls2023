<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_seq_as extends NKls_rule{	
	public function process(&$lines, &$target, &$subformulas, &$already_strings, $extra_param = false){
		//return;
		$error = false;
		static $lines_examined = array();
		static $strings_here = array();
				
		$newstructures = array();
		global $start;
		$startrun = microtime(true);
		
		
		static $premises = NULL;
		if(!is_array($premises)){
			$premises = array();
			foreach($lines as $line){
				if($line->source_type == 'premise'){
					$premises[] = $line->line_formula(); 
				}
				
			}
		}		
		
		$a = new NKls_formula('A');
		$b = new NKls_formula('B');
		$c = new NKls_formula('C');
		$ab = new NKls_formula($a,'&',$b);
		$ac = new NKls_formula($a,'&',$c);
		$nbc = new NKls_formula($ab,'&',$ac);
		
		static $newsubformulas;
		//extends original propositional subformulas 
		if(!is_array($newsubformulas)){
			$newsubformulas = array();
			foreach($subformulas as $string=>$subformula){
				if($extra_param == 'osf'){
					$ok = false;
					foreach($this->system->lines as $line){
						if($line->source_type == 'premise'){
							if($line->formula()->subformula1() == $subformula || $line->formula()->subformula2() == $subformula){
								$ok = true;
								break;
							}
						}
					}
					if(!$ok){
						continue;
					}
				}

				$props = array($subformula);
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
		
		static $subformulasord;
		if(!is_array($subformulasord)){
			$subformulasord = $newsubformulas;
			$ord = array();
			foreach($subformulasord as $k=>$s){
				$ord[$k] = strlen($s->string());
			}
			array_multisort($ord,SORT_ASC,$subformulasord);
		}
		//var_dump(array_keys($subformulasord));		die;
		
		$negtarget_string = $this->system->target->op() == '~' ?  $this->system->target->subformula1()->string() : $this->system->target->string();
		foreach($subformulasord as $l=>$subformula){
			if(in_array($l,$premises)) continue;
			if(isset($lines_examined[$l])) continue;
			$lines_examined[$l] = isset($lines_examined[$l]) ? $lines_examined[$l]++ : 0;
			
			if($l != $this->system->target->string() && $extra_param != 'nc'){
				$newstructures[] = array(
								'formula' => $subformula,
								'base_lines' => array(),
								'source_lines' => array(),
								'assumption' => true
							);
				$strings_here[$subformula->string()] = true;
			}
			
			$neg = $subformula->op() == '~' ? $subformula->subformula1() : new NKls_formula($subformula,'~');
			
			if(!isset($strings_here[$neg->string()])
				&& ($extra_param != 'nc' || $neg->string() == $negtarget_string)
				&& $extra_param != 'osf' 
				&& !in_array($neg->string(),$premises)
				 ){
				
				
				$newstructures[] = array(
							'formula' => $neg,
							'base_lines' => array(),
							'source_lines' => array(),
							'assumption' => true
						);
				$strings_here[$neg->string()] = true;
			}			
		}
		
		$this->system->log('As'.count($newstructures).'(lex '.count($lines_examined).'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		
		return $error ?: $newstructures;
		
	}
}
?>