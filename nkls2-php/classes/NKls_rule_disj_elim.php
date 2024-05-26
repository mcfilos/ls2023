<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_rule_disj_elim extends NKls_rule{
	protected $assumptions = array();
	protected $disjlines = array();
	
	
	public function line_back($line,$index){
		if(isset($this->assumptions[$index])){
			$this->assumptions[$index][] = $line->line_number();
			
		}
	}
	
	public function process(&$lines, &$target, &$subformulas, &$already_strings){
		$error = false;
		$lines_examined = array();
		$disj_examined = 0;
		static $line1_min = -1;
		static $line2_min = -1;
		static $line_as = -1;
		
		
		global $start;
		$startrun = microtime(true);
		$newstructures = array();
		$strings_here = array();
		
		
		if($this->assumptions ){
			foreach($lines as $l=>$line){
				foreach($lines as $ll=>$line2){
					 
					if($l == $ll || ($line1_min >= $l && $line2_min >= $ll)) continue;
					
					//same formula and not premises
					if(!$this->assumptions || $line->line_formula() != $line2->line_formula() || $line->source_type=='premise'|| $line2->source_type=='premise') continue;
					
					//one of the two should rely on an assumption
					$indexes = array();
					foreach($this->assumptions as $index=>$pair){
						$cond1 = in_array($pair[0],$line->base_lines) && in_array($pair[1],$line2->base_lines);
						$cond2 = in_array($pair[1],$line->base_lines) && in_array($pair[0],$line2->base_lines);
						$cond3 = false && array_intersect($pair,array_merge($line->base_lines,$line2->base_lines));
						if($cond1 || $cond2 || $cond3){
							$indexes[] = $index;		
						}
					}	
					if(!$indexes) {
						//die;
					}
					
					
					//avoid duplicates
					$ind = $l < $ll ? $l.'-'.$ll : $ll.'-'.$l;
					if(isset($lines_examined[$ind])) continue;
					$lines_examined[$ind] = true; 
					
					$newforms = $nextstrings = array();
					foreach($indexes as $index){
						$bls = array_unique(
									array_merge(
										array_diff($line->base_lines(),array($this->assumptions[$index][0])),
										array_diff($line2->base_lines(),array($this->assumptions[$index][1])),
										$this->disjlines[$index]->base_lines
									)	
								);
						$sls = array($index,$this->assumptions[$index][0],$this->assumptions[$index][1],$line->line_number(),$line2->line_number());
						sort($bls);
						sort($sls);
					
						$nextstring = $line->line_formula();
						$nextstringcheck = $nextstring.'-'.implode(',',$bls);				
					
						if(!isset($already_strings[$nextstringcheck]) && !isset($already_strings[$nextstring]) && !isset($strings_here[$nextstringcheck])){
							$newstructures[] = array(
										'formula' => $line->formula(),
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
			}		
		}
		
		
		
		$obj = $this;
		foreach($lines as $l=>$line){
			if($line_as >= $l) continue;
			$line_as = $l;
			
			if($line->formula()->op() != 'V' || strpos($line->line_source_info(),'IV') ===0) continue;
			
			$nextstring = $line->formula()->subformula1()->string();
			$nextstring2 = $line->formula()->subformula2()->string();
			$this->assumptions[$l] = array();
			$this->disjlines[$l] = $line;
		
			if(true || !isset($already_strings[$nextstring])){
				$newstructures[] = array(
						'formula' => $line->formula()->subformula1(),
						'base_lines' => $line->base_lines(),
						'source_lines' => array($line->line_number()),
						'assumption' => true,
						'line_callback' => function($line) use($obj,$l){
											$obj->line_back($line,$l);
									}
					);	
					
			}
			
			if(true || !isset($already_strings[$nextstring2])){
				$newstructures[] = array(
						'formula' => $line->formula()->subformula2(),
						'base_lines' => $line->base_lines(),
						'source_lines' => array($line->line_number()),
						'assumption' => true,
						'line_callback' => function($line) use($obj,$l){
											$obj->line_back($line,$l);
									}
					);	
					
			}
			$disj_examined++;
		}
		
		
		$this->system->log('EV'.count($newstructures).'(lex '.count($lines_examined).','.$disj_examined.'), max '.round(memory_get_peak_usage()/(1024*1024),2).'M, now '.round(memory_get_usage()/(1024*1024),2).'M, time '.round(microtime(true)-$start,4).', here '.round(microtime(true)-$startrun,4));
		return $error ?: $newstructures;	
	}
}

?>