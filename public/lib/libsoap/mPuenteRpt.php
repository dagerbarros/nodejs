<?php

include_once ('mPeticionRpt.php');
include_once ('mErrorresRprt.php');

class mPuenteRpt
{

private $_wsOperations = array("get","put","list","runReport","remove");

 private $_filesFolder;
 
  private $_wsdlUrl;
  
  private $_username;
  
  private $_password;
 
  public $outputHeader;
 
  public $ReportData;

  private $_attachmentFile = null;

  private $ExportHeaders = array
  (
  		"pdf"=>"application/pdf",
  		"xls"=>"application/ms-excel",
  		"xml"=>"application/xml",
  		"csv"=>"text/csv",
  		"rtf"=>"application/octet-stream",
  		"html"=>"text/html",
                "doc"=>"application/msword"
  );
  private $_clientHeaders = array();
  
  public function __construct($url, $username, $password,$files_dir = "") 
  {
    $this->_wsdlUrl = $url;
    $this->_username = $username;
    $this->_password = $password;
    
    if($files_dir != "")
    {
    	$this->createFilesDir($files_dir);
    }else
    {
  	   $_filesFolder = str_replace("\\", "/", dirname(dirname(__FILE__))) . '/carpeta_tempjasper';
  		$this->createFilesDir($_filesFolder);
    }

  }
 
  protected function createFilesDir($FilesDir)
  {	
  		if(!is_dir($FilesDir))
  		{
  			if(mkdir($FilesDir,0700,true))
  			{
  				$this->_filesFolder = $FilesDir."/";
  			}
  			
  		}else
  		{
  			chmod($FilesDir, 0700);
  			$this->_filesFolder = $FilesDir."/";
  		}
  	
  }
  /**
   * @return array
   */
  public function getHeader()
  {
  		return $this->_clientHeaders;
  }
    protected function getClientConnection()
  {
  		$clientConnection = null;
  		try 
  		{
	  		$clientConnection =  new SoapClient(null, array
	    	(
	        'location'  		=> $this->_wsdlUrl,
	        'uri'       		=> 'urn:',
	        'login'     		=> $this->_username,
	        'password'  		=> $this->_password,
	        'trace'    		=> true,
	        'exception'		=> 1,
	        'soap_version'  =>SOAP_1_1,
	        'style'    		=> SOAP_RPC,
	        'use'      		=> SOAP_LITERAL
	      ));
	  		
  		}catch(SoapFault $e)
  		{
  			print $e;
  		}
   	return $clientConnection;
  }
 
  protected function beforeProcess($ReportData)
  {
    	$xmlResponse = str_replace(array('&lt;', '&gt;','&quot;','<![CDATA[',']]>'), array('<', '>','"','',''), $ReportData);
  		$valor = $this->getDelimString($xmlResponse,"<returnMessage>","</returnMessage>");
  		throw new mErrorresRprt("JasperReports error( $valor)");
  }
  
  public function requestReport($report, $format, $params) 
  {
	$format = strtolower($format);
    	$reportData = null;
    
    if(!$this->existResource($report,"reportUnit"))
    {
    	 throw new mErrorresRprt("The specified resource $report doesn't exist");
    }
   
    if(!array_key_exists($format, $this->ExportHeaders))
    {
    	throw new mErrorresRprt("The $format format id Invalid!, valid formats Are: ".
    											  strtoupper(implode(",",array_keys($this->ExportHeaders))));
    }
    	$RequestXML = new mPeticionRpt("runReport",$params);
    	$RequestXML->addArgument("RUN_OUTPUT_FORMAT",$format);
    	$RequestXML->addArgument("PAGE","0");
    	$RequestXML->addResourceDescriptor("","",$report);
    
    try 
    {
    	
    	$client = $this->getClientConnection();
    	
        $result = $client->__soapCall('runReport',array(new SoapParam($RequestXML->getRequestString(),"requestXmlString")));
		
        $this->beforeProcess($client->__getLastResponse());
      
        $reportData = $this->parseReportData($client->__getLastResponseHeaders(), $client->__getLastResponse());
     
    }catch(SoapFault $exception) 
    {

      $responseHeaders = $client->__getLastResponseHeaders();
     
      if ($exception->faultstring == "looks like we got no XML document" &&
          strpos($responseHeaders, "Content-Type: multipart/related;") !== false) 
      {
      
        $reportData = $this->parseReportData($responseHeaders, $client->__getLastResponse());
      
      }else 
      {	
        throw new mErrorresRprt("Cliente Was Unable to parse Report Data");
      }
    }
    if ($reportData)
    {
      return $reportData;
    }
    
 }

 public function listResources($ResourcesDir)
 {
  		$RequestXML = new mPeticionRpt("list",null);
  		$RequestXML->addResourceDescriptor("","folder",$ResourcesDir);
  		$resourcesXML;
  	try
  	{
  	$client = $this->getClientConnection();
  	$result = $client->__soapCall('list',array(new SoapParam($RequestXML->getRequestString(),"requestXmlString")));
  	$xmlHead = $client->__getLastResponseHeaders();
  	$xmlString = $client->__getLastResponse();
  	$xmlResponse = $this->getDelimString($xmlString,"<listReturn xsi:type=\"xsd:string\">","</listReturn>");
	$xmlResponse = str_replace(array('&lt;', '&gt;','&quot;'), array('<', '>','"'), $xmlResponse);
	 $ResourcesXML = simplexml_load_string($xmlResponse);
  	}catch(SoapFault $e)
  	{
  		print "A SoapError has ocurred connecting to jasperServer repository: ".$e->getMessage();
  	}
  	return $ResourcesXML;
   }
 

protected  function getDelimString($string, $ini_delim, $end_delim)
{
    $string = " ".$string;
    $ini = strpos($string,$ini_delim);
    
    if ($ini == 0) return "";
       $ini += strlen($ini_delim);  
       $len = strpos($string,$end_delim,$ini) - $ini;
       
  return substr($string,$ini,$len);
}
protected function existResource($Resource,$ResourceType)
{
	 $idxStart = $this->lastIndexOf($Resource,"/");
	 
	 $_uriPath = substr($Resource,0,$idxStart);
	 
	 $ResourcesList = $this->listResources($_uriPath);
	 
	  foreach($ResourcesList->children() as $Node)
	  {
	 		if($Node["wsType"]==$ResourceType && $Node["uriString"]==$Resource)
	 		{
	 			return true;
	 		}	
	  }
	  return false;
}
protected function lastIndexOf($string,$item)
{
	$index=strpos(strrev($string),strrev($item));
	if ($index)
	{
		$index=strlen($string)-strlen($item)-$index;
		return $index;
	}
	else
		return -1;
}

  
  public function saveToFile($fileName,$ReportData)
  {
  		$Handler = null;
      try 
      {
     		$Handler = fopen($fileName,"wb");
     		fwrite($Handler, utf8_encode($ReportData));
     		fclose($Handler);
     		
      }catch(Exception $e)
      {
     		print "Cliente couldn't save the specified File Cliente no podía guardar el archivo especificado  $fileName, fault: $e->getMessage()";
      }
  }

  protected function parseReportData($responseHeaders, $responseBody) 
  {
	//die($responseBody);
    preg_match('/boundary="(.*?)"/', $responseHeaders, $matches);
    $boundary = $matches[1];
    $parts = explode($boundary, $responseBody);   
    $reportData = null;

    foreach($parts as $part) 
	 {
		
		if (strpos($part, "Content-Type: image/png") !== false) 
		{
			$InicioImg = strpos($part, "<") + 1;
			$TallaImg = (strpos($part, ">") - $InicioImg);
			
			$filename = substr($part, $InicioImg, $TallaImg) . '.png';
			
			$file = fopen("$this->_filesFolder$filename","wb");
			
			$inicioContenido = strpos($part, "PNG") - 1;
			$tallaContenido = (strpos($part, "--") - $inicioContenido) + 1;
			$contenidoImg = substr($part, $inicioContenido, $tallaContenido);
			fwrite($file, $contenidoImg);
			fclose($file);
			
		}
		
		if (strpos($part, "Content-Type: image/gif") !== false) 
		{
			$InicioImg = strpos($part, "<") + 1;
			$TallaImg = (strpos($part, ">") - $InicioImg);
				
			$filename = substr($part, $InicioImg, $TallaImg) . '.gif';
				
			$file = fopen("$this->_filesFolder$filename","wb");
				
			$inicioContenido = strpos($part, "GIF") - 1;
			$tallaContenido = (strpos($part, "--") - $inicioContenido) + 1;
			$contenidoImg = substr($part, $inicioContenido, $tallaContenido);
			fwrite($file, $contenidoImg);
			fclose($file);
		}

     if (strpos($part, "Content-Type: application/pdf") !== false) 
	  {
	  	
	$this->_clientHeaders[] = "Content-Type: application/pdf";
        $this->_clientHeaders[] ="Content-Transfer-Encoding: binary";
        $this->_clientHeaders[] ="Pragma: no-cache";
        $this->_clientHeaders[] = "Content-Disposition: attachment; filename=".$_SESSION['cedula'].".pdf";
        $InicioContenido = strpos($part, 'Content-Id: <report>') + 24;
        $tallaContenido = (strpos($part, "--") - $InicioContenido);
        $reportData = substr($part, strpos($part,'%PDF-'));
        break;
 
      }else if(strpos($part, "Content-Type: application/xls") !== false) 
	   {
	
	     $this->_clientHeaders[] = "Content-Type: application/ms-excel";
	     $this->_clientHeaders[] = "Content-Disposition: attachment; filename=Reporte.xls";
	     $InicioContenido = strpos($part, 'Content-Id: <report>') + 24;
	     $tallaContenido = (strpos($part, "--") - $InicioContenido);
             $reportData = substr($part, $InicioContenido,$tallaContenido);

        break;
        
      }else if(strpos($part, "application/msword") !== false) 
	   {
	
	     $this->_clientHeaders[] = "Content-Type: application/msword";
	     $this->_clientHeaders[] = "Content-Disposition: attachment; filename=Reporte.doc";
	     $InicioContenido = strpos($part, 'Content-Id: <report>') + 24;
	     $tallaContenido = (strpos($part, "--") - $InicioContenido);
             $reportData = substr($part, $InicioContenido,$tallaContenido);

        break;
        
      }else if(strpos($part, "Content-Type: application/vnd.ms-excel") !== false) 
	   {
	     $this->_clientHeaders[] = "Content-Type: text/csv";
        $this->_clientHeaders[] = "Content-Disposition: attachment; filename=Reporte.csv";     
        $contentStart = strpos($part, 'Content-Id: <report>') + 24;
        $reportData = substr($part, $contentStart);
        break;
        
      }else if(strpos($part,"Content-Type: text/html")!==false)
      {
        $contentStart = strpos($part, '<html>');
      	$contentLength = (strpos($part, '</html>') - $contentStart) + 7;
      	
      	$this->_clientHeaders[] = "Content-Type: text/html";
      	$reportData = substr($part, $contentStart, $contentLength);
      	
      }else if(strpos($part,"Content-Type: text/xml;") !==false)
      {
      	
      	$contentStart = strpos($part,'<xml>');
      	
      	$contentLength = (strpos($part,'</xml>')-$contentStart)+24;
      	
      	$this->_clientHeaders[] = "Content-Type:text;";
      	
      	$reportData = substr($part, $contentStart, $contentLength);
	
      }else if(strpos($part,"Content-Type: application/octet-stream")!==false)
      {
      	$this->_clientHeaders[] ="Content-Type";
      	$contentStart = strpos($part, 'Content-Id: <report>') + 24;
      	$reportData = substr($part, $contentStart);
      	$this->saveToFile("Report.rtf", $reportData);
      }
      
    } 
    return $reportData;
  }

}
?>