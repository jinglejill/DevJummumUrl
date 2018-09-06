<?php
    
    //Handy Code Provided by STEPHENCARR.NET**
    
    $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $Android= stripos($_SERVER['HTTP_USER_AGENT'],"Android");
    
    //check if user is using ipod, iphone or ipad...
    if( $iPod || $iPhone || $iPad ){
        //we send these people to Apple Store
        header('Location: https://itunes.apple.com/us/app/jummum/id1404154271?ls=1&mt=8'); // <-apple store link here
    }else if($Android){
        //we send these people to Android Store
        header('Location: http://www.example.com/'); // <-android store link here
    }
    //Handy Code Provided by STEPHENCARR.NET**
    
?>
