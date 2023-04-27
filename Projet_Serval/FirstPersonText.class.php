<?php
class FirstPersonText extends BaseClass 
{
    public function __construct(){
        parent::__construct();
    }

    public function get_Text( BaseClass $baseclass)
    {   
        $stmt = $baseclass->dbh->prepare("SELECT * FROM action WHERE map_id =$baseclass->_currentMapID");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        // var_dump($result['status']);
        if(!empty($result)){
        $status_action = $result['status'];
        }else{
        // var_dump($status_action);
            $status_action = 0;
        }        

        $stmt = $baseclass->dbh->prepare("SELECT * FROM text WHERE map_id = $baseclass->_currentMapID AND status_action= $status_action");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
       // $newText = $result["text"];
       // error_log("le texte affiché".$result["text"]);
      
            if(!empty($result["text"])){
                $newText =  $result["text"];
                return $newText;
            }
            return "";
        }
    }
