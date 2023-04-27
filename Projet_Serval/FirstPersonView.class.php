<?php
class FirstPersonView extends BaseClass 
{
    protected $compass;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function init()
    {
        $this->compass = "east";
    }
   public function getView(BaseClass $baseclass)
   {
    // var_dump($baseclass->_currentMapID);
    $stmt = $baseclass->dbh->prepare("SELECT * FROM action WHERE map_id =$baseclass->_currentMapID");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        if(!empty($result)){
        $status_action = $result['status'];
        }else{
            $status_action = 0;
        }        

        $stmt = $baseclass->dbh->prepare("SELECT * FROM images WHERE map_id = $baseclass->_currentMapID AND status_action= $status_action");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // $newMap = $result['path'];
        // $baseclass->_currentMap=$newMap;
        // return $baseclass->_currentMap;
      
            if(!empty($result["path"])){
                $newMap =  $result["path"];
                $baseclass->_currentMap = $newMap;
               
            }else{
                "chimichanga";
            }
            return $baseclass->_currentMap;
            
            
   }
    
    public function _AnimCompass(BaseClass $baseclass)
    {
        // error_log(" 1 fpv current angle : ".$baseclass->_currentAngle);
        $newcompass = $this->compass;
        switch($baseclass->_currentAngle){
            case 0 : {
                $newcompass =  "east";
                break;
            }
            case 90 : {
                $newcompass ="north";
                break;
            }
            case 180 : {
                $newcompass = "west";
                break;
            }
            case 270 : {
                $newcompass = "south";
                break;
            }
        }
        $this->compass = $newcompass;   
        return  $this->compass;
        
        // error_log(" 2 fpv current angle : ".$baseclass->_currentAngle);
        // error_log("fpv compass : ".$this->compass);
    }
    
    
}
?>


