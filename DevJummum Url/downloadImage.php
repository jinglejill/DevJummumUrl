<?php
    include_once("dbConnect.php");
    setConnectionValue("");
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    

    if(isset($_POST['imageFileName']))
    {
        $imageFileName = $_POST['imageFileName'];
        $type = $_POST['type'];
        $branchID = $_POST['branchID'];
    }
    else
    {
        $imageFileName = "201508131130161.jpg";
    }
    
    
    if($type == 1 || $type == 2)
    {
        $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
        $selectedRow = getSelectedRow($sql);
        $dbName = $selectedRow[0]["DbName"];
        $mainBranchID = $selectedRow[0]["MainBranchID"];
        if($branchID != $mainBranchID)
        {
            $sql = "select * from $jummumOM.branch where branchID = '$mainBranchID'";
            $selectedRow = getSelectedRow($sql);
            $dbName = $selectedRow[0]["DbName"];
        }
    }
    
    
    
    $b64image = "";
    if($imageFileName != "")
    {
        switch($type)
        {
            case 1://menu
                $imageFileName = "./$dbName/Image/Menu/$imageFileName";
                break;
            case 2://logo
                $imageFileName = "./$dbName/Image/Logo/$imageFileName";
                break;
            case 3://promotion
                $imageFileName = "./Image/Promotion/$imageFileName";
                break;
            case 4://reward
                $imageFileName = "./Image/Reward/$imageFileName";
                break;
        }
    }
    else
    {
        $imageFileName = "./Image/NoImage.jpg";
    }
    $filenameIn  = $imageFileName;
    
    
    
    // Check if file already exists
    if (file_exists($filenameIn))
    {
        $b64image = base64_encode(file_get_contents($filenameIn));
    }

    
    
    echo json_encode(array('base64String' => $b64image, 'post_image_filename' => $imageFileName));
    exit();
?>
