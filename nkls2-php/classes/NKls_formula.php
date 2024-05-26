<?php
/*
Optional course - Logic and software - Department of Philosophy - University of Bucharest. 2022. All rights reserved, except for educational purposes. mc@filos.ro
*/
class NKls_formula{
	protected $string = '';

	protected $op = NULL;
	protected $op_variable = NULL;
	protected $subformula1 = NULL;
	protected $subformula2 = NULL;
	protected static $variables_max = ['x','y','z','t','u','v','w'];
	public static $constants_used = ['a' => 0,
										'b' => 0
										];
	public static $variables_used = ['x' => 0,
									//'y' => 0
									];
	public static $ops = array(
		'~' => array('key' => '~', 'short' => 'neg', 'name' => 'negation', 'binary' => false, 'lng' => 'not', 'quantifier' => false),
		'&' => array('key' => '&', 'short' => 'conj', 'name' => 'conjunction', 'binary' => true, 'lng' => 'and', 'quantifier' => false),
		'V' => array('key' => 'V', 'short' => 'disj', 'name' => 'disjunction', 'binary' => true, 'lng' => 'or', 'quantifier' => false),
		'->' => array('key' => '->', 'short' => 'impl', 'name' => 'implication', 'binary' => true, 'lng' => 'implies', 'quantifier' => false),
		'<->' => array('key' => '<->', 'short' => 'equiv', 'name' => 'equivalence', 'binary' => true, 'lng' => 'just in case', 'quantifier' => false),
		'∀' => array('key' => '∀', 'short' => 'univ', 'name' => 'universal', 'binary' => false, 'lng' => 'forall', 'quantifier' => true),
		'∃' => array('key' => '∃', 'short' => 'exis', 'name' => 'existential', 'binary' => false, 'lng' => 'there is', 'quantifier' => true),
	);


	public function __construct($subformula1, $op = NULL, $subformula2 = NULL){
		//transform for quantifiers
		
		$op = mb_strlen($op) == 2 && mb_substr($op,0,1) === 'A' ? '∀'.mb_substr($op,1) : $op;
		$op = mb_strlen($op) == 2 && mb_substr($op,0,1) === 'E' ? '∃'.mb_substr($op,1) : $op;
		
		if(mb_substr($op,0,1) === '∀' || mb_substr($op,0,1) === '∃'){
			if(in_array(mb_substr($op,1), static::$variables_max)){
				$this->op_variable = mb_substr($op,1); 
				$op = mb_substr($op,0,1);
				//var_dump($subformula1);
				list($vars, $varsfree) = $subformula1->variables(false);
				if(!in_array($this->op_variable, $varsfree)){
					throw new Exception($op.' should only bind free variables');		
				}

			}
			else{
				throw new Exception($op.' can only bind variables');	
			}
			
		}
		
		if($op && !isset(static::$ops[$op])){
			throw new Exception($op.' is not accepted operator');
		}

		$this->op = $op;
		$this->subformula1 = $subformula1;
		$this->subformula2 = $subformula2;
		
		if(!$op && is_object($this->subformula1)){
			$this->subformula1 = $this->subformula1->string();
		}
		//extragem constanta
		$str = $this->is_atomic_predicate(true);
		if($str['term'] && $str['is_variable']  ){
			if(!isset(static::$variables_used[$str['term']])){
				static::$variables_used[$str['term']] = 0;
			}
			static::$variables_used[$str['term']]++;
			//var_dump($subformula1,static::$constants_used);
		}
		elseif($str['term'] && !$str['is_variable']  ){
			if(!isset(static::$constants_used[$str['term']])){
				static::$constants_used[$str['term']] = 0;
			}
			static::$constants_used[$str['term']]++;
			//var_dump($subformula1,static::$constants_used);
		}	
		
		$this->string_create(true);
	}

	public static function ops_unary(){
		$ret = [];
		foreach(static::$ops as $op){
			if(!$op['binary']){
				$ret[] = $op['key'];
			}
		}
		return $ret;
	}

	public static function op_by_short($short){
		$ret = false;
		foreach(static::$ops as $op){
			if($op['short'] == $short){
				$ret = $op;
				break;
			}
		}
		return $ret;
	}	
	
	//not implemented, should validate line
	public function is_wff_closed(){
		$ret = $this->is_propositional() || !$this->is_withfreevariables();
		return $ret;
	}
	public function is_atomic(){
		return !$this->op();
	}

	public function is_withfreevariables(){

	}

	public function variables($one_or_recursive = true, $constants = false, $quantifier_variables_above = array(), &$processed = NULL){
		//var_dump($this->string().'-'.json_encode($quantifier_variables_above));
		$list = array();
		$list_freevariables = array();
		
		$det = $this->is_atomic_predicate(true);
		if(!empty($det['term']) && ($constants xor $det['is_variable'])){
			$list[] = $det['term'];
			//e obligatoriu libera
			if($det['is_variable'] && !in_array($det['term'], $quantifier_variables_above)){
				
				$list_freevariables[] = $det['term'];
			}
		}
		//trece prin subformule
		if(!$this->is_atomic() && !$one_or_recursive){
			//are variabila de cuantificare
			
			$quantifier_variables = $this->op_variable ? array_merge([$this->op_variable],$quantifier_variables_above) : $quantifier_variables_above;
			$processed = is_null($processed) ? array($this) : $processed;
		
			foreach($this->subformulas_flat(true) as $formula){
				if(in_array($formula,$processed)) continue;
				$processed[] = $formula;

				//var_dump($this->string().'-'.$formula->string().'-'.json_encode($quantifier_variables));
				list($subterms,$subterms_freevariables) = $formula->variables(false,$constants, $quantifier_variables, $processed);
				$list = array_unique(array_merge($list, $subterms));
				$list_freevariables = array_unique(array_merge($list_freevariables, $subterms_freevariables));
			}			
		}

		return [$list,$list_freevariables];
	}


	public function mass_clone($var_to_const = true){
		$ret = $alreadystrings = array();
		$freevars = $this->variables_free_flat(false);
		$consts = array_keys(static::$constants_used);

		//var_dump($var_to_const, $this->string(), $this->variables_free_flat(false));
		if($this->string() === 'Px & Py'){
			//var_dump('HERE '.$this->string());
		}
		$l = count($freevars) * count(static::$constants_used);
		for($i =0; $i< $l; $i++){
			$obj = $this;
			
			foreach($freevars as $var){
				$successes = 0;
				$acv = array_count_values($alreadystrings);
				foreach($consts as $const){
					//echo "\n".'pass for '.$var.' - '. $const.' with '.$obj->string().' '.json_encode($alreadystrings)."\n";
					$propobj = $obj->clone_replaced([$var => $const]);
					//var_dump('CCC',$propobj);
					if(!$propobj){
						continue;
					}
					$varsfree = $propobj->variables_free_flat(false);
					if(!$varsfree && !isset($alreadystrings[$propobj->string()])){
						$alreadystrings[$propobj->string()] = $obj->string();
						$ret[$propobj->string()] = $propobj;
						//var_dump('CL: '.$propobj->string());
						$successes++;
					}
					elseif($varsfree && ($acv[$propobj->string()] ?? 0 ) < count($consts) ){
						$obj = $propobj;
					}
					
				}				
			}
			if(count($alreadystrings) >= $l){
				break;
			}
		}
		return $ret;

	}

	

	//apelata cu [x => a] pt instantiere cu [a => x] pt cuantificare
	public function clone_replaced($var_to_const = array(), $const_to_var = array(), $variants = false ){
		//var_dump('v-'.json_encode($variants));
		$retobj = false;
		$ret = array();
		$searches_var = !!$var_to_const;
		$term = $searches_var ? array_keys($var_to_const)[0] : array_keys($const_to_var)[0];
		$replacer = $searches_var ? reset($var_to_const) : reset($const_to_var);

		if($this->is_atomic()){
			list($terms, $vars_free) = $this->variables(true,!$searches_var);
			if($terms && $terms[0] === $term){
				$retobj = clone $this;
				$retobj->subformula1_set(str_replace($term,$replacer, $this->subformula1()));
				$retobj->string_create(true);
				$ret[] = $retobj;
			}	
		}
		//daca nu e atomica
		else{
			$realvariants = array();
			//daca se cere variants, fiecare produce 1, 2 sau 3: cu 1 inlocuita, cu 2

			$done = false;
			$clone1 = $this->subformula1()->clone_replaced($var_to_const, $const_to_var,$variants);
			
			if($clone1){
				$done = true;
			}
			$clone2 = $this->subformula2 ?  $this->subformula2->clone_replaced($var_to_const, $const_to_var, $variants) : false;
			if($clone2){
				$done = true;
			}

			if($done){
				$retobj = clone $this;
				if($clone1){
					$obj = is_array($clone1) ? $clone1[0] : $clone1;
					$retobj->subformula1_set($obj);
				}
				if($clone2){
					$obj = is_array($clone2) ? $clone2[0] : $clone2;
					$retobj->subformula2_set($obj);
				}
				$retobj->string_create(true);
				$ret[] = $retobj;
				
				if($variants){
					//doar cu 1
					if($clone1){
						$obj = is_array($clone1) && $clone1 ? $clone1[0] : $clone1;
						$new = clone $this;
						$new->subformula1_set($obj);
						$new->string_create(true);
						$ret[] = $new;
					}
					//doar cu 2
					if($clone2){
						$obj = is_array($clone2) && $clone2 ? $clone2[0] : $clone2;
						$new = clone $this;
						$new->subformula2_set($obj);
						$new->string_create(true);
						$ret[] = $new;
					}
				}

			}
		}
		
		return $variants ? $ret : reset($ret);
	}
	
	public function variables_flat($one_or_recursive = true){
		list($list,$list_freevariables) = $this->variables($one_or_recursive);
		return $list;
	}

	public function variables_free_flat($one_or_recursive = true){
		list($list,$list_freevariables) = $this->variables($one_or_recursive);
		return $list_freevariables;
	}
	
	public function constants_flat($one_or_recursive = true){
		list($list,$list_freevariables) = $this->variables($one_or_recursive,true);
		return $list;
	}

	public function is_atomic_predicate($parse_structure = false){
		$ret = $default = array(
				'predicate' => false,
				'term' => false,
				'is_variable' => false,
			);
		if($this->is_atomic() && mb_strlen($this->subformula1) == 2){
			$ret['predicate'] = strtoupper(mb_substr($this->subformula1,0,1)) == mb_substr($this->subformula1,0,1) 
								? mb_substr($this->subformula1,0,1) 
								: false;
			$ret['term'] = strtolower(mb_substr($this->subformula1,1,1)) == mb_substr($this->subformula1,1,1) 
								? mb_substr($this->subformula1,1,1) : false;
			$ret['is_variable'] =$ret['term'] && in_array($ret['term'], static::$variables_max); 
		}
		if(!$parse_structure){
			return $ret['predicate'] && $ret['term'] ? true : false;
		}
		else{
			
			return $ret['predicate'] && $ret['term'] ? $ret : $default;
		}
	}

	public function is_propositional(){
		$ret = false;
		if($this->is_atomic() && mb_strlen($this->subformula1) == 1 && strtoupper($this->subformula1) == $this->subformula1){
			$ret = true;
		}
		elseif(!$this->is_atomic()){
			$ret = true;
			$opobj = $this->op_obj();
			if($opobj['quantifier']){
				$ret = false;
			}
			else{
				foreach($this->subformulas_flat(true) as $formula){
					if($formula === $this) continue;
					if(!$formula->is_propositional()){
						$ret = false;
						break;
					}
				}
			}
		}
		return $ret;
	}
	
	
	public function string_create($save = false){
		$ret = '';
		$opobj = $this->op_obj();

		if($this->is_atomic()){
			$ret = $this->subformula1;
		}
		else if(!$opobj['binary'] && $opobj['quantifier']){		
			$ret = '('. $this->op.$this->op_variable.')'.$this->subformula1->string(true);

		}	
		else if(!$opobj['binary']){
			
			$ret = $this->op.$this->subformula1->string(true);

		}	
		else{
			$ret = $this->subformula1->string(true).' '.$this->op.' '.$this->subformula2->string(true);
		}
		if($save){
			$this->string = $ret;
		}
		return $ret;
	}
	
	public function string($external_brackets = false){
		$ret = $this->string;
		if($external_brackets && !$this->is_atomic() && !in_array($this->op, static::ops_unary())){
			$ret = '('.$ret.')';
		}
		return $ret;
	}
	
	public function op(){
		return $this->op;
	}
	public function op_variable(){
		return $this->op_variable;
	}
	public function op_obj(){
		return $this->op ? static::$ops[$this->op] : false;
	}

	public function subformula1(){
		return $this->subformula1;
	}
	public function subformula1_set($obj){
		$this->subformula1 = $obj;
		return true;
	}
	public function subformula2_set($obj){
		$this->subformula2 = $obj;
		return true;
	}

	public function subformula2(){
		return $this->subformula2;
	}
	
	// array of arrays of decomposition, main to secondary, left to right
	public function subformulas(){
		$ret = array(
					0 => is_object($this->subformula1) ? $this->subformula1->subformulas() :  $this->subformula1, 
					1 => $this->op, 
					2 => is_object($this->subformula2) ? $this->subformula2->subformulas() :  $this->subformula2  );
		return $ret;
	}
	
	public function subformulas_flat($as_objects = false){
		if(!$as_objects){
			$ret = array_merge(
					array($this->string() => $this->string()),
					is_object($this->subformula1) ? $this->subformula1->subformulas_flat() :   array(),
					is_object($this->subformula2) ? $this->subformula2->subformulas_flat() :   array()
				);
		}
		else{
			$ret = array_merge(
					array($this->string() => $this),
					is_object($this->subformula1) 
							? $this->subformula1->subformulas_flat(true) 
							:  array(),
					is_object($this->subformula2) 
							? $this->subformula2->subformulas_flat(true) 
							:  array()
				);
		}
		return $ret;
	}
	
	//op no
	public function length(){
		$ret = 0;
		if(!$this->is_atomic()){
			$l1 = $this->subformula1->length();
			$l2 = $this->subformula2 ? $this->subformula2->length() : 0;
			$ret = max($l1,$l2) +1;
		}
		return $ret;
	} 	
	
	
}

?>