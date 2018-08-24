<?php

class mPeticionRpt
{
	private $Request;
	private $resourceDescriptor;
	private $Parameters;
	public function __construct($OperationName,$Parameters)
	{
		$this->Request = new SimpleXMLElement("<request />");
		$this->Request->addAttribute("operationName",$OperationName);
		$this->Request->addAttribute("locale","es");
		$this->Parameters = $Parameters;
	}
	
	public function addArgument($ArgumentName,$argumentValue)
	{
		$argument = $this->Request->addChild('argument',$argumentValue);
		$argument->addAttribute("name",$ArgumentName);
	}
	
	public function addResourceDescriptor($name = "",$wsType = "",$uriString ="",$isNew = "false")
	{
		$resourceDescriptor = $this->Request->addChild('resourceDescriptor');
		
		$resourceDescriptor->addAttribute("name",$name);
		$resourceDescriptor->addAttribute("wsType",$wsType);
		$resourceDescriptor->addAttribute("uriString",$uriString);
		$resourceDescriptor->addAttribute("isNew",$isNew);

		$this->resourceDescriptor = $resourceDescriptor;
		$this->addArrayParam($this->Parameters);
	}
    
	protected function addArrayParam($Params)
	{
		if (is_array($Params))
		{
			foreach($Params as $Param)
			{
				if(!is_array($Param))
				{
					throw new mErrorresRprt("Cada parámetro debe ser un array asociativo consistente en [nombre, valor] llaves y ella es respectivos valores");
				
				}else if(!array_key_exists("name",$Param)|| !array_key_exists("value", $Param))
				{
					throw new mErrorresRprt("Matriz de parámetros no válido, debe especificar Keys array (nombre => nombre_opcion, valor => param_value)");
				} 

				$parameter = $this->resourceDescriptor->addChild('parameter',$Param['value']);
				$parameter->addAttribute('name', $Param['name']);

			}
		}
	}
	
	public function getRequestString()
	{
		return $this->Request->asXML();
	}
}