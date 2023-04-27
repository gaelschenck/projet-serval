<?php 

class BaseClass 
{
    protected $_currentX;
    protected $_currentY;
    protected $_currentAngle;
    protected $_currentMap;
    protected $_currentMapID;
    protected $dbh;

    public function __construct() //connexion à la base de données
    {
        $this->dbh = new Database();
        
    }
    public function set_currentX(int $_currentX)//coordonées X
    {
        $this->_currentX = $_currentX;
    }
    public function get_currentX()
    {
        return $this->_currentX;
    }
    public function set_currentY(int $_currentY)//coordonées Y
    {
        $this->_currentY = $_currentY;
    }
    public function get_currentY()
    {
        return $this->_currentY;
    }
    public function set_currentAngle(int $_currentAngle)//Angle de vue
    {
        $this->_currentAngle = $_currentAngle;
    }
    public function get_currentAngle()
    {
        return $this->_currentAngle;
    }
    public function set_currentMap(string $_currentMap)//coordonées X
    {
        $this->_currentMap = $_currentMap;
    }
    public function get_currentMap()
    {
        return $this->_currentMap;
    }
    public function set_currentMapID(int $_currentMapID)
    {
        $this->_currentMapID = $_currentMapID;
    }
    public function get_currentMapID()
    {
        return $this->_currentMapID;
    }
    public function init()
    {
        $this->_currentX = 0;
        $this->_currentY = 1;
        $this->_currentAngle = 0;
        $this->_currentMap = "01-0.jpg";
        $this->_currentMapID = 1;
        
        $stmt = $this->dbh->prepare(" UPDATE action SET status = 0 ");
        $stmt->execute();
    }

    // ***** Vérifie que le mouvement vers la cible est possible *****
    
    private function checkMove(int $newX, int $newY, int  $_currentAngle) //vérifie la possibilité de déplacement vers une position cible
    {
        $stmt = $this->dbh->prepare("SELECT id FROM map WHERE coordx = $newX AND coordy = $newY AND direction = $_currentAngle");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
// *****  test déplacement et changement d'angle de vue *** //

    public function checkForward()//vérifie la possibilité de déplacement avant
    { 
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newX++;
                break;
            }
            case 90 : {
                $newY++;
                break;
            }
            case 180 : {
                $newX--;
                break;
            }
            case 270 : {
                $newY--;
                break;
            }
        }
        return $this->checkMove($newX, $newY, $this->_currentAngle);
    }
    
    public function checkBack()//vérifie la possibilité de déplacement arrière
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newX--;
                break;
            }
            case 90 : {
                $newY--;
                break;
            }
            case 180 : {
                $newX++;
                break;
            }
            case 270 : {
                $newY++;
                break;
            }
        }
        return $this->checkMove($newX, $newY, $this->_currentAngle); 
    }
    public function checkRight()//vérifie la possibilité de déplacement vers la droite
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newY--;
                break;
            }
            case 90 : {
                $newX++;
                break;
            }
            case 180 : {
                $newY++;
                break;
            }
            case 270 : {
                $newX--;
                break;
            }
        }
        return $this->checkMove($newX, $newY, $this->_currentAngle);
    }
    public function checkLeft()//vérifie la possibilité de déplacement vers la gauche
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newY++;
                break;
            }
            case 90 : {
                $newX--;
                break;
            }
            case 180 : {
                $newY--;
                break;
            }
            case 270 : {
                $newX++;
                break;
            }
        }
            return $this->checkMove($newX, $newY, $this->_currentAngle);
            
    }
   
// ***** Effectue un déplacement ou un changement d'angle *****

    public function goMove($newX, $newY )
    {
        $stmt = $this->dbh->prepare("SELECT id FROM map WHERE coordx = $newX AND coordy = $newY AND direction = $this->_currentAngle");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $map_id = $result["id"];
        $this->_currentMapID = $map_id;

        $stmt = $this->dbh->prepare("SELECT * FROM images WHERE $map_id=map_id");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newMap = $result["path"];

        $this->_currentX = $newX;
        $this->_currentY = $newY;
        $this->_currentMap = $newMap;
        
    }

    public function goTurn($newAngle)
    {
        $stmt = $this->dbh->prepare("SELECT id FROM map WHERE coordx = $this->_currentX AND coordy = $this->_currentY AND direction = $newAngle");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $map_id = $result["id"];

        $stmt = $this->dbh->prepare("SELECT * FROM images WHERE $map_id = map_id");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newMap = $result["path"];

        $this->_currentAngle = $newAngle;
        $this->_currentMap = $newMap;
        $this->_currentMapID = $map_id;        
    }

    public function goForward()//aller en avant
    {   
        
        $newX = $this->_currentX;
        $newY = $this->_currentY;
            // var_dump($newX);
            // var_dump($newY);
            // var_dump($this->_currentAngle);
        switch($this->_currentAngle){
            case 0 : {
                $newX++;
                break;
            }
            case 90 : {
                $newY++;
                break;
            }
            case 180 : {
                $newX--;
                break;
            }
            case 270 : {
                $newY--;
                break;
            }
        }
        // var_dump($newX);
        // var_dump($newY);
            return $this->goMove($newX, $newY, $this->_currentAngle);
    }
       
    public function goBack()//aller en arrière
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newX--;
                break;
            }
            case 90 : {
                $newY--;
                break;
            }
            case 180 : {
                $newX++;
                break;
            }
            case 270 : {
                $newY++;
                break;
            }
        }
        return $this->goMove($newX, $newY, $this->_currentAngle);
    }
    
    public function goLeft()//aller à droite
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newY++;
                break;
            }
            case 90 : {
                $newX--;
                break;
            }
            case 180 : {
                $newY--;
                break;
            }
            case 270 : {
                $newX++;
                break;
            }
        }
        return $this->goMove($newX, $newY, $this->_currentAngle);
    }
    public function goRight()//aller à gauche
    {
        $newX = $this->_currentX;
        $newY = $this->_currentY;
        switch($this->_currentAngle){
            case 0 : {
                $newY--;
                break;
            }
            case 90 : {
                $newX++;
                break;
            }
            case 180 : {
                $newY++;
                break;
            }
            case 270 : {
                $newX--;
                break;
            }
        }
        return $this->goMove($newX, $newY, $this->_currentAngle);
    }
    public function turnRight()//tourner à droite
    {
        
        $newAngle = $this->_currentAngle;
        switch($this->_currentAngle){
            case 0 : {
                $newAngle =270;
                break;
            }
            case 90 : {
                $newAngle = 0;
                break;
            }
            case 180 : {
                $newAngle = 90;
                break;
            }
            case 270 : {
                $newAngle = 180;
                break;
            }
        }
        return $this->goTurn($newAngle);
    }
        
        
    
    public function turnLeft()//tourner à gauche
    {
        error_log("BaseClass::turnLeft()");

        $newAngle = $this->_currentAngle;
        error_log("on part de ".$newAngle);

        switch($this->_currentAngle){
         
            case 0 : {
                $newAngle = 90;
                break;
            }
            case 90 : {
                $newAngle = 180;
                break;
            }
            case 180 : {
                $newAngle = 270;
                break;
            }
            case 270 : {
                $newAngle = 0;
                break;
            }
        }
        return $this->goTurn($newAngle);
    }
}