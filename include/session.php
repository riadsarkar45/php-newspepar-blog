<?php
session_start();
function failed_alert(){
    if(isset($_SESSION["message"])){
        $Output="<div class=\"alert alert-danger\">";
        $Output .= htmlentities($_SESSION["message"]);
        $Output .="</div>";
        $_SESSION["message"]=null;
        return $Output;


    }
}
function success_alert(){
    if(isset($_SESSION["Message"])){
        $Output="<div class=\"alert alert-success\">";
        $Output .= htmlentities($_SESSION["Message"]);
        $Output .="</div>";
        $_SESSION["Message"]=null;
        return $Output;


    }
}
 ?>
