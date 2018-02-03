<?php
    
    if(isset($_REQUEST['controller']))
    {
        if ($_REQUEST['controller'] == "Heroes" || $_REQUEST['controller'] == "Masters" || $_REQUEST['controller'] == "Places") include "scripts/get_data.php";
        else include "scripts/main.php";
    }
    else include "scripts/main.php";
?>