<?php
session_start();
function chargerClasse($classe)
{
    require $classe . '.class.php';
}
    spl_autoload_register('chargerClasse');
$dbh = new Database();

$baseclass = new BaseClass();
$fpv = new FirstPersonView();
$fpt = new FirstPersonText();
$fpa = new FirstPersonAction();


if (empty($_POST)){ 
    $baseclass->init();
    $fpv->init();
    
    }else{
        $baseclass->set_currentX($_POST['X']);
        $baseclass->set_currentY($_POST['Y']);
        $baseclass->set_currentAngle($_POST['Angle']);
        $baseclass->set_currentMapID($_POST['MapID']);
    }
//     var_dump("POST");
// var_dump($_POST);
// var_dump("baseclass");
// var_dump($baseclass);
// var_dump("fpv");
// var_dump($fpv);


if (isset($_POST['forward'])){
    $baseclass->goForward();
}
if (isset($_POST['left'])){
    $baseclass->goLeft();
}
if (isset($_POST['back'])){
    $baseclass->goBack();
}
if (isset($_POST['right'])){
    $baseclass->goRight();
    
}
if (isset($_POST['turnleft'])){
    $baseclass->turnLeft();
    $fpv->_AnimCompass($baseclass);
}
if (isset($_POST['turnright'])){
    $fpv->_AnimCompass($baseclass);
    $baseclass->turnRight();
}
if (isset($_POST['action'])){
    $fpa->doAction($baseclass);
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./assets/style.css">
        <title>Doom-like - Projet Serval</title>
    </head>
    <body>
    <div class="background">
        <img src="./images/<?php echo $fpv->getView($baseclass);?>">
    </div>
        <div class="buttons">
            <table>
                <form method="POST" action="index.php">
                    <div class="control">
                        <button type="submit" name="turnleft">TL</button>

                        <button type="submit" name="forward"<?php if($baseclass->checkForward() == TRUE){echo "enabled";} else {echo"disabled";} ?>>^</button>

                        <button type="submit" name="turnright">TR</button>
                    </div>
                    <div>
                        <button type="submit" id ="left" name="left"<?php if($baseclass->checkLeft() == TRUE){echo "enabled";} else {echo"disabled";} ?>><</button>

                        <button type="submit" name="action"<?php if($fpa->checkAction($baseclass) == TRUE){echo "enabled";} else {echo"disabled";} ?>>X</button>

                        <button type="submit" name="right"<?php if($baseclass->checkRight() == TRUE){echo "enabled";} else {echo"disabled";} ?>>></button>
                    </div>
                    <div>
                        <button type="submit" name="back"<?php if($baseclass->checkBack() == TRUE){echo "enabled";} else {echo"disabled";} ?>>V</button>
                    </div>
                    <input type="hidden" name="X" value="<?php echo $baseclass->get_currentX(); ?>">
                    <input type="hidden" name="Y" value="<?php echo $baseclass->get_currentY(); ?>">
                    <input type="hidden" name="Angle" value="<?php echo $baseclass->get_currentAngle(); ?>">
                    <input type="hidden" name="MapID" value="<?php echo $baseclass->get_currentMapID(); ?>">
                </form>
            </table>
        </div>
        <div>
            <img class="compass <?php  echo $fpv->_AnimCompass($baseclass); ?>">
        </div>
    </div>
    <div class="text"><?php echo $fpt->get_Text($baseclass);  ?>
    </div>
    </body>
</html>