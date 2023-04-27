<?php
class FirstPersonAction extends BaseClass 
{
    public function __construct(){
        parent::__construct();
    }

    public function checkAction(BaseClass $baseclass)
    {
        
        $stmt = $baseclass->dbh->prepare("SELECT * FROM action WHERE map_id = $baseclass->_currentMapID");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result['map_id']);
        if($result){
        $map_id = $result['map_id'];
            if(!empty($map_id) && ($result['status']!== $this->guessStatusAction($map_id))){
                // if (($map_id==="3") || ($map_id==="14")){
                    return TRUE;
                }else{
                    return FALSE;
                }
            // }
        }else{
            return FALSE;
        }
    }

    public function guessStatusAction($map_id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM map WHERE id = $map_id");
        $stmt->execute();
    }

    public function doAction(BaseClass $baseclass)
    {   
        $stmt = $baseclass->dbh->prepare("SELECT * FROM action WHERE map_id =$baseclass->_currentMapID ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION[''] = $result['item_id'];

        $stmt = $baseclass->dbh->prepare("UPDATE action SET status ='1' WHERE map_id=$baseclass->_currentMapID");
        $stmt->execute();


    }
}