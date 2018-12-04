
<?php
    include_once("dbConnect.php");
    setConnectionValue("");
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
//    // Set autocommit to off
//    mysqli_autocommit($con,FALSE);
//    writeToLog("set auto commit to off");
    
    
//    $sql = "SHOW DATABASES LIKE '%demo_jummum_om_%'";
//    $selectedRow = getSelectedRow($sql);
//    for($i=0; $i<sizeof($selectedRow); $i++)
    {
//        echo $selectedRow[$i][0] . "<br>";
//        $dbName = $selectedRow[$i][0];
//        $sql = file_get_contents('./sqlStatement.sql');
//        $sql = str_replace("#dbName#",$dbName,$sql);
//

        $sql = "SELECT * INTO OUTFILE 'testBackupFile.sql' FROM receipt limit 10";
//        echo $sql . "<br>";
//        $ret = doMultiQueryTask($sql);
//        if($ret != "")
//        {
//            mysqli_rollback($con);
////            putAlertToDevice();
//            echo json_encode($ret);
//            exit();
//        }
//        mysqli_close($con);
//        setConnectionValue("");
    }
    
    
    
    
    //do script successful
//    mysqli_commit($con);
//    mysqli_close($con);
    
    
    
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
