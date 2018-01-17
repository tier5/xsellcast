<?php namespace App\Storage\MacroBuilder;

trait MacroBuilder{

	public function macroCall()
	{

		if(array($this->macros)){
			foreach($this->macros as $path){
				$path = resource_path('macros/' . str_replace('.', '/', $path) . '.php');
				require($path);
			}
		}
	}

}

?>