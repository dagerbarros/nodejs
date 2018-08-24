<?php
class Conexion extends PDO
{
    private $cnx1;
    
    public function __construct() {
        require_once ('config_2.php');
        $typedb1 = $bd1['typedb'];
        $host1 = $bd1['host'];
        $port1 = $bd1['port'];
        $dbname1 = $bd1['dbname'];
        $user1 = $bd1['user'];
        $passwd1 = $bd1['passwd'];
        try {
            $this->cnx1= parent::__construct("$typedb1".":host='".$host1."';port='".$port1."';dbname='".$dbname1."';user='".$user1."';password='".$passwd1."'");
            
        } catch (Exception $e) {
            echo 'Error al conectar con la primera base de datos '. $e->getMessage();
        }
    }
    function ejecutar($query,$ejecutante,$ejecuta){
       $conex = $this->conectar();
        if($ejecuta == 'insert') {
            try {
                $evalua=$conex->prepare($query);
                $evalua->execute();
                $result = $evalua->fetch(PDO::FETCH_ASSOC);
                extract($result);
            return $clave;
            } catch (Exception $e) {
                echo 'Error 101 en la función '.$ejecutante.' '. $e->getMessage();
            }
        }else if($ejecuta=='select'){
            try {
                $evalua = $conex->prepare($query);
                $evalua->execute();
                $result = $evalua->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (Exception $e) {
                echo 'Error 102 en la función '.$ejecutante.' '. $e->getMessage();
            }
        }else if($ejecuta=='update'){
           try {
                $evalua = $conex->prepare($query);
                $evalua->execute();
                $result = $evalua->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (Exception $e) {
                echo 'Error 103 en la función '.$ejecutante.' '. $e->getMessage();
            } 
        }else if($ejecuta=='delete'){
            try {
                $evalua = $conex->prepare($query);
                $evalua->execute();
                $result = $evalua->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (Exception $e) {
                echo 'Error 104 en la función '.$ejecutante.' '. $e->getMessage();
            } 
        }
    }
   public function mClose(){
       
       $this->cnx1=null;
   }
   function conectar(){
       require_once ('config_2.php');
        $typedb1 = $bd1['typedb'];
        $host1 = $bd1['host'];
        $port1 = $bd1['port'];
        $dbname1 = $bd1['dbname'];
        $user1 = $bd1['user'];
        $passwd1 = $bd1['passwd'];
       try {
            $conec = new PDO("$typedb1".":host='".$host1."';port='".$port1."';dbname='".$dbname1."';user='".$user1."';password='".$passwd1."'");
            $conec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo 'Error 100 '. $e->getMessage();
        }
        return $conec;
   }
}               
?>