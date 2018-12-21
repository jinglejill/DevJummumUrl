<?php
    include_once('dbConnect.php');
    setConnectionValue("");
    

    $arrDeviceToken = array();
    array_push($arrDeviceToken,'90bfe9bf70d26fef6ed12b68b6a984608ac3b71e99725e02485000366deb4b04');
    sendPushNotificationJummum($arrDeviceToken,'tttt','eeee','abc',1,null);

?>

