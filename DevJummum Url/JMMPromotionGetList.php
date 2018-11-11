<?php
    include_once("dbConnect.php");
    setConnectionValue("");
//    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
//    printAllPost();
    ini_set("memory_limit","-1");
    
    
    //*****
    header("Content-Type: application/json");
    
    // get the lower case rendition of the headers of the request
    
    $headers = array_change_key_case(getallheaders());
    
    // extract the content-type
    
    if (isset($headers["content-type"]))
    {
        $content_type = $headers["content-type"];
        writeToLog("set contentType: " . $content_type);
    }
    else
    {
        $content_type = "";
        writeToLog("not set contentType: " . $content_type);
    }
    
    // if JSON, read and parse it
    if ($content_type == "application/json" || strpos($content_type,"application/json")!== false)
    {
        // read it
        
        $handle = fopen("php://input", "rb");
        $raw_post_data = '';
        while (!feof($handle)) {
            $raw_post_data .= fread($handle, 8192);
        }
        fclose($handle);
        
        // parse it
        
        $data = json_decode($raw_post_data, true);
    }
    else
    {
        // report non-JSON request and exit
    }
    
    writeToLog("file: " . basename(__FILE__) . ", user: " . $data["modifiedUser"]);
    writeToLog("json data: " . json_encode($data));
    
    
    //*****
//    if(isset($_POST["voucherCode"]))
    {
        $voucherCode = $data["voucherCode"];
    }
//    if(isset($_POST["userAccountID"]))
    {
        $userAccountID = $data["userAccountID"];
    }
//    if(isset($_POST["branchID"]))
    {
        $branchID = $data["branchID"];
    }
//    if(isset($_POST["totalAmount"]))
    {
        $sumSpecialPrice = $data["sumSpecialPrice"];
    }
    {
        $arrOrderTaking = $data["orderTaking"];
        for($i=0; $i<sizeof($arrOrderTaking); $i++)
        {
            $orderTaking = $arrOrderTaking[$i];
            
            
            $otOrderTakingID[$i] = $orderTaking["orderTakingID"];
            $otBranchID[$i] = $orderTaking["branchID"];
            $otCustomerTableID[$i] = $orderTaking["customerTableID"];
            $otMenuID[$i] = $orderTaking["menuID"];
            $otQuantity[$i] = $orderTaking["quantity"];
            $otSpecialPrice[$i] = $orderTaking["specialPrice"];
            $otPrice[$i] = $orderTaking["price"];
            $otTakeAway[$i] = $orderTaking["takeAway"];
            $otTakeAwayPrice[$i] = $orderTaking["takeAwayPrice"];
            $otNoteIDListInText[$i] = $orderTaking["noteIDListInText"];
            $otNotePrice[$i] = $orderTaking["notePrice"];
            $otDiscountValue[$i] = $orderTaking["discountValue"];
            $otOrderNo[$i] = $orderTaking["orderNo"];
            $otStatus[$i] = $orderTaking["status"];
            $otReceiptID[$i] = $orderTaking["receiptID"];
            $otModifiedUser[$i] = $orderTaking["modifiedUser"];
            $otModifiedDate[$i] = $orderTaking["modifiedDate"];
        }
    }
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    
    
    
    $warningMsg;
    $voucherValid = 1;
    $sql = "select * from promotion where voucherCode = '$voucherCode';";
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow) == 0)
    {
        //คูปองส่วนลดไม่ถูกต้อง -> ไม่มี voucher code นี้
        $voucherValid = 0;
        $warningMsg = "ไม่มี Voucher Code นี้";
    }
    
    
    if($voucherValid)
    {
        $sql = "select * from promotion where voucherCode = '$voucherCode' and date_format(now(),'%Y-%m-%d') < date_format(usingStartDate,'%Y-%m-%d');";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) > 0)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> voucher code นี้ยังไม่ได้เริ่มใช้
            $voucherValid = 0;
            $warningMsg = "Voucher Code นี้ยังไม่ได้เริ่มใช้";
        }
    }
    
    
    if($voucherValid)
    {
        $sql = "select * from promotion where voucherCode = '$voucherCode' and date_format(now(),'%Y-%m-%d') > date_format(usingEndDate,'%Y-%m-%d');";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) > 0)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> voucher code นี้หมดอายุแล้ว
            $voucherValid = 0;
            $warningMsg = "Voucher Code นี้หมดอายุแล้ว";
        }
    }
    
    
    $sql = "select * from promotion where voucherCode = '$voucherCode' and date_format(now(),'%Y-%m-%d') between date_format(usingStartDate,'%Y-%m-%d') and date_format(usingEndDate,'%Y-%m-%d');";
    $promotion = getSelectedRow($sql);
    $promotionID = $promotion[0]["PromotionID"];
    $noOfLimitUse = $promotion[0]["NoOfLimitUse"];
    $noOfLimitUsePerUser = $promotion[0]["NoOfLimitUsePerUser"];
    $noOfLimitUsePerUserPerDay = $promotion[0]["NoOfLimitUsePerUserPerDay"];
    $minimumSpending = $promotion[0]["MinimumSpending"];
    $maxDiscountAmountPerDay = $promotion[0]["MaxDiscountAmountPerDay"];
    $allowEveryone = $promotion[0]["AllowEveryone"];
    if($voucherValid)
    {
        $hasVoucherInPromotionTable = 1;
        if(!$allowEveryone)
        {
            //checkUser allow มัั๊ย
            $sql = "select * from promotionUser where useraccountID = '$userAccountID'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                //คูปองส่วนลดไม่ถูกต้อง -> คุณไม่สามารถใช้คูปองนี้ได้
                $voucherValid = 0;
                $warningMsg = "คุณไม่สามารถใช้คูปองนี้ได้";
            }
        }
    }
    
    
    if($voucherValid)
    {
        $sql = "select * from promotionBranch where promotionID = '$promotionID' and branchID = '$branchID'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) == 0)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> คูปองไม่สามารถใช้ได้กับร้านนี้
            $voucherValid = 0;
            $warningMsg = "คูปองไม่สามารถใช้ได้กับร้านนี้";
        }
    }
    
    
    if($voucherValid)
    {
        //NoOfLimitUse
        $sql = "select count(*) UsedCount from userPromotionUsed where promotionID = '$promotionID'";
        $selectedRow = getSelectedRow($sql);
        $usedCount = $selectedRow[0]["UsedCount"];
        if($noOfLimitUse > 0 && $usedCount >= $noOfLimitUse)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> จำนวนสิทธิ์ครบแล้ว
            $voucherValid = 0;
            $warningMsg = "จำนวนสิทธิ์ครบแล้ว";
        }
    }
    
            
    if($voucherValid)
    {
        $sql = "select count(*) UsedCount from userPromotionUsed where promotionID = '$promotionID' and userAccountID = '$userAccountID'";
        $selectedRow = getSelectedRow($sql);
        $usedCountPerUser = $selectedRow[0]["UsedCount"];
        if($noOfLimitUsePerUser > 0 && $usedCountPerUser >= $noOfLimitUsePerUser)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> คุณใช้สิทธิ์ครบแล้ว
            $voucherValid = 0;
            $warningMsg = "คุณใช้สิทธิ์ครบแล้ว";
        }
    }
    
    
    if($voucherValid)
    {
        $sql = "select count(*) UsedCount from userPromotionUsed where promotionID = '$promotionID' and userAccountID = '$userAccountID' and date_format(modifiedDate,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
        $selectedRow = getSelectedRow($sql);
        $usedCountPerUserPerDay = $selectedRow[0]["UsedCount"];
        if($noOfLimitUsePerUserPerDay > 0 && $usedCountPerUserPerDay >= $noOfLimitUsePerUserPerDay)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> วันนี้คุณใช้สิทธิ์ครบแล้ว
            $voucherValid = 0;
            $warningMsg = "วันนี้คุณใช้สิทธิ์ครบแล้ว";
        }
    }
    
    
    if($voucherValid)
    {
        //minimumSpending
        if($sumSpecialPrice < $minimumSpending)
        {
            //คูปองส่วนลดไม่ถูกต้อง -> ยอดสั่งซื้อขั้นต่ำไม่ถึง
            $voucherValid = 0;
            $warningMsg = "ยอดสั่งซื้อขั้นต่ำไม่ถึง";
        }
    }
    
    
    if($voucherValid)
    {
        $sql = "select ifnull(sum(discountValue),0) SumDiscountValue from userPromotionUsed left join receipt on userPromotionUsed.receiptID = receipt.receiptID where promotionID = '$promotionID' and userAccountID = '$userAccountID' and date_format(userPromotionUsed.modifiedDate,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
        $selectedRow = getSelectedRow($sql);
        $sumDiscountValue = $selectedRow[0]["SumDiscountValue"];
        if($maxDiscountAmountPerDay > 0)//ใช้ moreToGo แทน maxDiscount ทั้งกรณีใช้ได้ครั้งเดียวต่อวัน และหลายครั้งต่อวัน
        {
            if($sumDiscountValue >= $maxDiscountAmountPerDay)
            {
                //คูปองส่วนลดไม่ถูกต้อง -> วันนี้คุณใช้สิทธิ์ครบแล้ว
                $voucherValid = 0;
                $warningMsg = "วันนี้คุณใช้สิทธิ์ครบแล้ว";
            }
            else
            {
                if($noOfLimitUsePerUserPerDay == 1 && $noOfLimitUsePerUser > 1 && $usedCountPerUser > 0)
                {
                    $sql = "select * from promotionDiscount where promotionID = '$promotionID' and time = ($usedCountPerUser+1)";
                    $selectedRow = getSelectedRow($sql);
                    $maxDiscountAmountPerDay = $selectedRow[0]["MaxDiscountAmountPerDay"];
                    $moreDiscountToGo = $maxDiscountAmountPerDay - $sumDiscountValue;
                }
                else
                {
                    $moreDiscountToGo = $maxDiscountAmountPerDay - $sumDiscountValue;
                }
            }
        }
        else
        {
            $moreDiscountToGo = -1;
        }
    }
    
    
    
    if(!$hasVoucherInPromotionTable)
    {
        //search at table rewardRedemption,rewardPoint, promoCode
        $warningMsg2 = "";
        $voucherValid2 = 1;
        $currentDateTime = date('Y-m-d H:i:s');
        $sql = "SELECT rewardRedemption.*,promoCode.PromoCodeID FROM `rewardpoint` left join promoCode on rewardPoint.promoCodeID = promoCode.promoCodeID left join RewardRedemption on promocode.rewardRedemptionID = RewardRedemption.rewardRedemptionID WHERE MemberID = '$userAccountID' and rewardpoint.status = -1 and ((TIME_TO_SEC(timediff('$currentDateTime', rewardpoint.ModifiedDate)) < rewardredemption.WithInPeriod) or (rewardRedemption.WithInPeriod = 0 and '$currentDateTime'<rewardRedemption.usingEndDate)) and promoCode.Code = '$voucherCode' and promoCode.status = 1";
        $selectedRow = getSelectedRow($sql);
        $minimumSpending = $selectedRow[0]["MinimumSpending"];
        $maxDiscountAmountPerDay = $selectedRow[0]["MaxDiscountAmountPerDay"];
        $rewardRedemptionID = $selectedRow[0]["RewardRedemptionID"];
        $promoCodeID = $selectedRow[0]["PromoCodeID"];
        if($voucherValid2)
        {
            if(sizeof($selectedRow)==0)
            {
                $sql = "SELECT rewardRedemption.* FROM `rewardpoint` left join promoCode on rewardPoint.promoCodeID = promoCode.promoCodeID left join RewardRedemption on promocode.rewardRedemptionID = RewardRedemption.rewardRedemptionID WHERE MemberID = '$userAccountID' and rewardpoint.status = -1 and ((TIME_TO_SEC(timediff('$currentDateTime', rewardpoint.ModifiedDate)) < rewardredemption.WithInPeriod) or (rewardRedemption.WithInPeriod = 0 and '$currentDateTime'<rewardRedemption.usingEndDate)) and promoCode.Code = '$voucherCode' and promoCode.status = 2";
                $selectedRow = getSelectedRow($sql);
                if(sizeof($selectedRow)>0)
                {
                    $voucherValid2 = 0;
                    $warningMsg2 = "Voucher Code นี้ใช้ไปแล้ว";
                }
                else
                {
                    $voucherValid2 = 0;
                    $warningMsg2 = "ไม่มี Voucher Code นี้";
                }
            }
        }
        
        
        
        if($voucherValid2)
        {
            $sql = "select * from rewardRedemptionBranch where rewardRedemptionID = '$rewardRedemptionID' and branchID = '$branchID'";
            $selectedRow = getSelectedRow($sql);
            if(sizeof($selectedRow) == 0)
            {
                //คูปองส่วนลดไม่ถูกต้อง -> คูปองไม่สามารถใช้ได้กับร้านนี้
                $voucherValid2 = 0;
                $warningMsg2 = "คูปองไม่สามารถใช้ได้กับร้านนี้";
            }
        }
        
        
        
        if($voucherValid2)
        {
            //minimumSpending
            if($sumSpecialPrice < $minimumSpending)
            {
                //คูปองส่วนลดไม่ถูกต้อง -> ยอดสั่งซื้อขั้นต่ำไม่ถึง
                $voucherValid2 = 0;
                $warningMsg2 = "ยอดสั่งซื้อขั้นต่ำไม่ถึง";
            }
        }
        
        
        
        if($voucherValid2)
        {
            $sql = "select ifnull(sum(discountValue),0) SumDiscountValue from userRewardRedemptionUsed left join receipt on userRewardRedemptionUsed.receiptID = receipt.receiptID where RewardRedemptionID = '$rewardRedemptionID' and userAccountID = '$userAccountID' and date_format(userRewardRedemptionUsed.modifiedDate,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
            $selectedRow = getSelectedRow($sql);
            $sumDiscountValue = $selectedRow[0]["SumDiscountValue"];
            if($maxDiscountAmountPerDay > 0)
            {
                if($sumDiscountValue >= $maxDiscountAmountPerDay)
                {
                    //คูปองส่วนลดไม่ถูกต้อง -> วันนี้คุณใช้สิทธิ์ครบแล้ว
                    $voucherValid2 = 0;
                    $warningMsg2 = "วันนี้คุณใช้สิทธิ์ครบแล้ว";
                }
                else
                {
                    $moreDiscountToGo = $maxDiscountAmountPerDay - $sumDiscountValue;
                }
            }
            else
            {
                $moreDiscountToGo = -1;
            }
        }
    }
    
    
    
    if($voucherValid)
    {
        writeToLog("noOfLimitUsePerUserPerDay: " . $noOfLimitUsePerUserPerDay);
        writeToLog("noOfLimitUsePerUser: " . $noOfLimitUsePerUser);
        if($noOfLimitUsePerUserPerDay == 1 && $noOfLimitUsePerUser > 1 && $usedCountPerUser > 0)
        {
            $sql = "select Promotion.`PromotionID`, `MainBranchID`, `StartDate`, `EndDate`, `UsingStartDate`, `UsingEndDate`, `Header`, `SubTitle`, `ImageUrl`, PromotionDiscount.`DiscountType`, PromotionDiscount.`DiscountAmount`, `ShopDiscount`, `JummumDiscount`, `SharedDiscountType`, `SharedDiscountAmountMax`, `MinimumSpending`, PromotionDiscount.`MaxDiscountAmountPerDay`, `AllowEveryone`, `AllowDiscountForAllMenuType`, `DiscountGroupMenuID`,DiscountMenuMaxQuantity, `NoOfLimitUse`, `NoOfLimitUsePerUser`, `NoOfLimitUsePerUserPerDay`, `VoucherCode`, `TermsConditions`, `Type`, `OrderNo`, `Status`, Promotion.`ModifiedUser`, Promotion.`ModifiedDate`, $moreDiscountToGo as MoreDiscountToGo,0 PromoCodeID from promotion left join PromotionDiscount on PromotionDiscount.promotionID = PromotionDiscount.promotionID where voucherCode = '$voucherCode' and date_format(now(),'%Y-%m-%d') between date_format(usingStartDate,'%Y-%m-%d') and date_format(usingEndDate,'%Y-%m-%d') and PromotionDiscount.time = ($usedCountPerUser+1);";
        }
        else
        {
            $sql = "select promotion.*, $moreDiscountToGo as MoreDiscountToGo,0 PromoCodeID from promotion where voucherCode = '$voucherCode' and date_format(now(),'%Y-%m-%d') between date_format(usingStartDate,'%Y-%m-%d') and date_format(usingEndDate,'%Y-%m-%d');";
        }
        $sql .= "select '' as Text;";
        $sql .= "select 1 as Text";
    }
    else if(!$voucherValid && $hasVoucherInPromotionTable)
    {
        $sql = "select * from promotion where 0;";
        $sql .= "select '$warningMsg' as Text;";
        $sql .= "select 1 as Text";
    }
    else if(!$voucherValid && !$hasVoucherInPromotionTable)
    {
        if($voucherValid2)
        {
            $sql = "select $moreDiscountToGo as MoreDiscountToGo,RewardRedemptionID,AllowDiscountForAllMenuType,DiscountType,DiscountAmount, `ShopDiscount`, `JummumDiscount`, `SharedDiscountType`, `SharedDiscountAmountMax`,MainBranchID,DiscountGroupMenuID,DiscountMenuMaxQuantity,$promoCodeID PromoCodeID from rewardRedemption where rewardRedemptionID = '$rewardRedemptionID';";
            $sql .= "select '' as Text;";
            $sql .= "select 2 as Text";
        }
        else
        {
            $sql = "select * from promotion where 0;";
            $sql .= "select '$warningMsg2' as Text;";
            $sql .= "select 2 as Text";
        }
    }

    
    
    //*********** get error message,discount type,discount Amount, discount value, promotionID, rewardRedemptionID, promoCodeID
    $sqlList = explode(";",$sql);
    $promotionList = getSelectedRow($sqlList[0]);
    $warningMsgList = getSelectedRow($sqlList[1]);
    $typeList = getSelectedRow($sqlList[2]);

    
//    $selectedRowList = executeMultiQueryArray($sql);
//    $promotionList = $selectedRowList[0];
//    $warningMsgList = $selectedRowList[1];
//    $typeList = $selectedRowList[2];
//
    
    
    $warningMsg = $warningMsgList[0]["Text"];
    if($warningMsg)
    {
        $sql = "select '$warningMsg' as Error";
        $jsonEncode = executeMultiQueryArray($sql);
        $response = array('success' => true, 'data' => $jsonEncode, 'error' => null, 'status' => 1);
        echo json_encode($response);
        
        
        // Close connections
        mysqli_close($con);
        exit();
    }
    
    
    
    //get dbName
    $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $dbName = $selectedRow[0]["DbName"];
    
    
    
    //validate เมนูที่เลือก ร่วมโปรมั๊ย
    $hasDiscountForMenu = 0;
    $promotion = $promotionList[0];
    $discountGroupMenuID = $promotion["DiscountGroupMenuID"];
    if($discountGroupMenuID)
    {
        $sql = "select * from $dbName.DiscountGroupMenuMap where DiscountGroupMenuID = '$discountGroupMenuID' and status = 1";
        $selectedRow = getSelectedRow($sql);
        $discountGroupMenuMapList = $selectedRow;
        for($j=0; $j<sizeof($discountGroupMenuMapList); $j++)
        {
            $discountMenuID = $discountGroupMenuMapList[$j]["MenuID"];
            for($i=0; $i<sizeof($arrOrderTaking); $i++)
            {
                $menuID = $otMenuID[$i];
                if($discountMenuID == $menuID)
                {
                    $hasDiscountForMenu = 1;
                    break;
                }
            }
        }
    }
    else
    {
        for($i=0; $i<sizeof($arrOrderTaking); $i++)
        {
            $sql = "select MenuType.* from $dbName.Menu left join $dbName.MenuType on Menu.MenuTypeID = MenuType.MenuTypeID where menuID = '$otMenuID[$i]'";
            $selectedRow = getSelectedRow($sql);
            $menuType = $selectedRow[0];

            //เช็คว่า เมนูที่สั่งได้ส่วนลดมั๊ย
            if(($promotion["AllowDiscountForAllMenuType"] || $menuType["AllowDiscount"]) && ($otSpecialPrice[$i] == $otPrice[$i]))
            {
                $hasDiscountForMenu = 1;
                break;
            }
        }
    }
    
    
    
    if(!$hasDiscountForMenu)
    {
        $sql = "select 'โค้ดที่ใส่ไม่สามารถใช้กับเมนูที่คุณเลือก' as Error";
        $jsonEncode = executeMultiQueryArray($sql);
        $response = array('success' => true, 'data' => $jsonEncode, 'error' => null, 'status' => 1);
        echo json_encode($response);
        
        
        // Close connections
        mysqli_close($con);
        exit();
    }
    else
    {
        $discountValue = 0;
        if($promotion["DiscountGroupMenuID"])
        {
            $countMenuQuantity = 0;
            
            
            for($j=0; $j<sizeof($discountGroupMenuMapList); $j++)
            {
                $discountMenuID = $discountGroupMenuMapList[$j]["MenuID"];
                
                for($i=0; $i<sizeof($arrOrderTaking); $i++)
                {
                    $menuID = $otMenuID[$i];
                    if($discountMenuID == $menuID)
                    {
                        $countMenuQuantity++;
                        if($promotion["DiscountMenuMaxQuantity"] == 0 || $countMenuQuantity <= $promotion["DiscountMenuMaxQuantity"])
                        {
                            if($promotion["DiscountType"] == 1)//baht
                            {
                                $discountValue += $promotion["DiscountAmount"];
                            }
                            else if($promotion["DiscountType"] == 2)//percent
                            {
                                $discountEach = $promotion["DiscountAmount"] * 0.01 * ($otPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i]);
                                $discountEach = round($discountEach * 100) * 0.01;
                                $discountValue += $discountEach;
                            }
                        }
                        else
                        {
                            break;
                        }
                    }
                }
            }
        }
        else
        {
//            $countMenuQuantity = 0;
            for($i=0; $i<sizeof($arrOrderTaking); $i++)
            {
                $sql = "select MenuType.* from $dbName.Menu left join $dbName.MenuType on Menu.MenuTypeID = MenuType.MenuTypeID where menuID = '$otMenuID[$i]'";
                $selectedRow = getSelectedRow($sql);
                $menuType = $selectedRow[0];
                
                
                //เช็คว่า เมนูที่สั่งได้ส่วนลดมั๊ย
                if(($promotion["AllowDiscountForAllMenuType"] || $menuType["AllowDiscount"]) && ($otSpecialPrice[$i] == $otPrice[$i]))
                {
//                    $countMenuQuantity++;
//                    if($promotion["DiscountMenuMaxQuantity"] == 0 || $countMenuQuantity <= $promotion["DiscountMenuMaxQuantity"])
                    {
//                        if($promotion["DiscountType"] == 1) //baht
//                        {
//                            $discountValue += $promotion["DiscountAmount"];
//                        }
//                        else
                        if($promotion["DiscountType"] == 2) //percent
                        {
                            $discountEach = $promotion["DiscountAmount"] * 0.01 * ($otPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i]);
                            $discountEach = round($discountEach * 100) * 0.01;
                            $discountValue += $discountEach;
                        }
                    }
//                    else
//                    {
//                        break;
//                    }
                }
            }
            
            
            if($promotion["DiscountType"] == 1)
            {
                $discountValue = $promotion["DiscountAmount"];
                
                $totalPriceGetDiscount = 0;
                for($i=0; $i<sizeof($arrOrderTaking); $i++)
                {
                    $sql = "select MenuType.* from $dbName.Menu left join $dbName.MenuType on Menu.MenuTypeID = MenuType.MenuTypeID where menuID = '$otMenuID[$i]'";
                    $selectedRow = getSelectedRow($sql);
                    $menuType = $selectedRow[0];
                    
                    
                    //เช็คว่า เมนูที่สั่งได้ส่วนลดมั๊ย
                    if(($promotion["AllowDiscountForAllMenuType"] || $menuType["AllowDiscount"]) && ($otSpecialPrice[$i] == $otPrice[$i]))
                    {
                        $totalPriceGetDiscount += $otSpecialPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i];
                    }
                }
                
                $discountValue = $discountValue > $totalPriceGetDiscount?$totalPriceGetDiscount:$discountValue;
            }
        }
        
        
        if($promotion["MoreDiscountToGo"] != -1)
        {
            $discountValue = $discountValue > $promotion["MoreDiscountToGo"]?$promotion["MoreDiscountToGo"]:$discountValue;
        }
    }
    //***********
    

    $sql = "select '' as Error, '$discountValue' As DiscountValue";
    

    /* execute multi query */
    $jsonEncode = executeMultiQueryArray($sql);
    $response = array('success' => true, 'data' => $jsonEncode, 'error' => null, 'status' => 1);
    echo json_encode($response);



    // Close connections
    mysqli_close($con);
//
?>
