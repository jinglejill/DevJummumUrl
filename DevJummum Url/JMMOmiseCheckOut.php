<?php
    include_once("dbConnect.php");
    setConnectionValue("");
//    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
//    printAllPost();
    
    
    
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
    
    
    {
        $omiseToken = $data["omiseToken"];
        $amount = $data["amount"];
        $voucherCode = $data["voucherCode"];
        $luckyDrawSpend = $data["luckyDrawSpend"];
    }
    
    {
        $receiptID = $data["receiptID"];
        $branchID = $data["branchID"];
        $customerTableID = $data["customerTableID"];
        $memberID = $data["memberID"];
        $servingPerson = $data["servingPerson"];
        $customerType = $data["customerType"];
        $openTableDate = $data["openTableDate"];
        $totalAmount = $data["totalAmount"];
        $cashAmount = $data["cashAmount"];
        $cashReceive = $data["cashReceive"];
        $creditCardType = $data["creditCardType"];
        $creditCardNo = $data["creditCardNo"];
        $creditCardAmount = $data["creditCardAmount"];
        $transferDate = $data["transferDate"];
        $transferAmount = $data["transferAmount"];
        $remark = $data["remark"];
        $specialPriceDiscount = $data["specialPriceDiscount"];
        $discountType = $data["discountType"];
        $discountAmount = $data["discountAmount"];
        $discountValue = $data["discountValue"];
        $discountReason = $data["discountReason"];
        $serviceChargePercent = $data["serviceChargePercent"];
        $serviceChargeValue = $data["serviceChargeValue"];
        $priceIncludeVat = $data["priceIncludeVat"];
        $vatPercent = $data["vatPercent"];
        $vatValue = $data["vatValue"];
        $beforeVat = $data["beforeVat"];
        $status = $data["status"];
        $statusRoute = $data["statusRoute"];
        $receiptNoID = $data["receiptNoID"];
        $receiptNoTaxID = $data["receiptNoTaxID"];
        $receiptDate = $data["receiptDate"];
        $sendToKitchenDate = $data["sendToKitchenDate"];
        $deliveredDate = $data["deliveredDate"];
        $mergeReceiptID = $data["mergeReceiptID"];
        $buffetReceiptID = $data["buffetReceiptID"];
        $voucherCode = $data["voucherCode"];
        $shopDiscount = $data["shopDiscount"];
        $jummumDiscount = $data["jummumDiscount"];
        $modifiedUser = $data["modifiedUser"];
        $modifiedDate = $data["modifiedDate"];
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
    
    
    $arrOrderNote = $data["orderNote"];
    for($i=0; $i<sizeof($arrOrderNote); $i++)
    {
        $orderNote = $arrOrderNote[$i];
        
        
        $onOrderNoteID[$i] = $orderNote["orderNoteID"];
        $onOrderTakingID[$i] = $orderNote["orderTakingID"];
        $onNoteID[$i] = $orderNote["noteID"];
        $onQuantity[$i] = $orderNote["quantity"];
        $onModifiedUser[$i] = $orderNote["modifiedUser"];
        $onModifiedDate[$i] = $orderNote["modifiedDate"];
    }
    
    $userAccountID = $memberID;
    

    writeToLog('token : ' . $omiseToken);
    writeToLog('amount : ' . $amount);
    
    
    //validate shop opening time*******************
    $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $selectedDbName = $selectedRow[0]["DbName"];
    
    
    
    $inOpeningTime = 0;
    $sql = "select * from $selectedDbName.Setting where keyName = 'customerOrderStatus'";
    $selectedRow = getSelectedRow($sql);
    $customerOrderStatus = $selectedRow[0]["Value"];
    if($customerOrderStatus == 1)
    {
        $inOpeningTime = 1;
    }
    else if($customerOrderStatus == 2)
    {
        $inOpeningTime = 0;
    }
    else
    {
        //get today's opening time
        $strDate = date("Y-m-d");
        $currentDate = date("Y-m-d H:i:s");
        $dayOfWeek = date('w', strtotime($strDate));
        $sql = "select * from $selectedDbName.OpeningTime where day = '$dayOfWeek' order by day,shiftNo";
        $selectedRow = getSelectedRow($sql);
        
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $day = $selectedRow[$i]["Day"];
            $startTime = $selectedRow[$i]["StartTime"];
            $endTime = $selectedRow[$i]["EndTime"];
            
            
            
            $intStartTime = intVal(str_replace(":","",$startTime));
            $intEndTime = intVal(str_replace(":","",$endTime));
            if($intStartTime < $intEndTime)
            {
                $startDate = date($strDate . " " . $startTime . ":00");
                $endDate = date($strDate . " " . $endTime . ":00");
                if($startDate<=$currentDate && $currentDate<=$endDate)
                {
                    $inOpeningTime = 1;
                }
            }
            else
            {
                $nextDate = date("Y-m-d", strtotime($strDate. ' + 1 days'));
                $startDate = date($strDate . " " . $startTime . ":00");
                $endDate = date($nextDate . " " . $endTime . ":00");
                if($startDate<=$currentDate && $currentDate<=$endDate)
                {
                    $inOpeningTime = 1;
                }
            }
        }
    }
    
    

    
    if(!$inOpeningTime)
    {
        writeToLog("omise charge fail, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
        $response = array('status' => '2', 'msg' => 'ทางร้านไม่ได้เปิดระบบการสั่งอาหารด้วยตนเองตอนนี้ ขออภัยในความไม่สะดวกค่ะ');
        echo json_encode($response);
        exit();
    }
    /////////******************
    
    
    
    
    //validate menu
    //validate price
    //validate menuNote
    $arrOrderTakingNew = array();
    $arrOrderNoteNew = array();
    $orderChanged = 0;
    $sql = "select * from $jummumOM.branch where branchID = '$branchID';";
    $selectedRow = getSelectedRow($sql);
    $dbName = $selectedRow[0]["DbName"];
    $takeAwayFee = $selectedRow[0]["TakeAwayFee"];
    for($i=0; $i<sizeof($arrOrderTaking); $i++)
    {
        $menuID = $arrOrderTaking[$i]["menuID"];
        $sql = "select menu.*, case when specialPriceProgramDay.specialPriceProgramDayID is null then menu.price else ifnull(specialPriceProgram.SpecialPrice,menu.price) end AS SpecialPrice from $dbName.menu LEFT JOIN $dbName.specialPriceProgram ON menu.menuID = specialPriceProgram.menuID AND date_format(now(),'%Y-%m-%d') between date_format(specialPriceProgram.startDate,'%Y-%m-%d') and date_format(specialPriceProgram.endDate,'%Y-%m-%d') left join $dbName.specialPriceProgramDay on specialPriceProgram.specialPriceProgramID = specialPriceProgramDay.specialPriceProgramID and specialPriceProgramDay.Day = dayOfWeek(now())-1 where status = 1 and menu.menuID = '$menuID'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) == 0)
        {
            $orderChanged = 1;
            writeToLog("menu status not active, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
        }
        else
        {
            //buffetMap
            if($buffetReceiptID)
            {
                $sql = "select Menu.* from receipt LEFT JOIN ordertaking ON receipt.ReceiptID = ordertaking.ReceiptID LEFT JOIN $dbName.BuffetMenuMap on orderTaking.MenuID = BuffetMenuMap.BuffetMenuID LEFT JOIN $dbName.Menu on BuffetMenuMap.MenuID = Menu.MenuID where receipt.receiptID = '$buffetReceiptID' and BuffetMenuMap.menuID is not null and BuffetMenuMap.Status = 1 and Menu.status = 1 and menu.menuID = '$menuID'";
                $selectedRow2 = getSelectedRow($sql);
                if(sizeof($selectedRow2) == 0)
                {
                    $orderChanged = 1;
                    writeToLog("buffet menu status not active, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
                    continue;
                }
            }
            
            
            
            $arrOrderTakingNew[] = $arrOrderTaking[$i];
            
            
            //specialPrice
            if($selectedRow[0]["SpecialPrice"] != $arrOrderTaking[$i]["specialPrice"])
            {
                $arrOrderTakingNew[sizeof($arrOrderTakingNew)-1]["specialPrice"] = $selectedRow[0]["SpecialPrice"];
                $arrOrderTakingNew[sizeof($arrOrderTakingNew)-1]["price"] = $selectedRow[0]["Price"];
                $orderChanged = 1;
                writeToLog("menu special price changed, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
            }
            
            
            //takeawayPrice
            if($arrOrderTaking[$i]["takeAway"] && $takeAwayFee != $arrOrderTaking[$i]["takeAwayPrice"])
            {
                $arrOrderTakingNew[sizeof($arrOrderTakingNew)-1]["takeAwayPrice"] = $takeAwayFee;
                $orderChanged = 1;
                writeToLog("take away price changed, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
            }
            
            
            //notePrice
            if($arrOrderTaking[$i]["noteIDListInText"] != "")
            {
                $noteIDListInTextNew = "";
                $notePriceNew = 0;
                $arrNoteID = explode(",",$arrOrderTaking[$i]["noteIDListInText"]);
                for($j=0; $j<sizeof($arrNoteID); $j++)
                {
                    $noteID = $arrNoteID[$j];
                    $sql = "select * from $dbName.menuNote left join $dbName.Note on menuNote.NoteID = Note.NoteID where menuNote.status = 1 and Note.status = 1 and menuID = '$menuID' and menuNote.noteID = '$noteID';";
                    $selectedRow = getSelectedRow($sql);
                    if(sizeof($selectedRow) == 0)
                    {
                        $orderChanged = 1;
                        writeToLog("menuNote or note status not active, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
                    }
                    else
                    {
                        $noteIDListInTextNew .= $noteIDListInTextNew == ""?$noteID:",".$noteID;
                        
                        
                        
                        //orderNote
                        for($k=0; $k<sizeof($arrOrderNote); $k++)
                        {
                            if($arrOrderNote[$k]["orderTakingID"] == $arrOrderTaking[$i]["orderTakingID"] && $arrOrderNote[$k]["noteID"] == $noteID)
                            {
                                $arrOrderNoteNew[] = $arrOrderNote[$k];
                                break;
                            }
                        }
                        $notePriceNew += $selectedRow[0]["Price"] * $arrOrderNoteNew[sizeof($arrOrderNoteNew)-1]["quantity"];
                    }
                }
                if($arrOrderTaking[$i]["notePrice"] != $notePriceNew)
                {
                    $orderChanged = 1;
                    writeToLog("note price changed, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
                }
             

                
                $arrOrderTakingNew[sizeof($arrOrderTakingNew)-1]["noteIDListInText"] = $noteIDListInTextNew;
                $arrOrderTakingNew[sizeof($arrOrderTakingNew)-1]["notePrice"] = $notePriceNew;
            }
        }
    }
    
    
    //lucky draw ที่ได้ในครั้งนี้
    $sql = "select * from $dbName.setting where keyName = 'luckyDrawSpend'";
    $arrMultiResult = executeMultiQueryArray($sql);
    $luckyDrawSpendSettingList = $arrMultiResult[0];
    $luckyDrawSpendSetting = $luckyDrawSpendSettingList[0];
    
    if($luckyDrawSpendSetting->Value && $luckyDrawSpendSetting->Value != $luckyDrawSpend)
    {
        $orderChanged = 1;
        writeToLog("lucky draw changed, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
    }
    
    
    //make key capital letter for OrderTaking
    $arrOrderTakingNewCapitalKey = array();
    for($i=0; $i<sizeof($arrOrderTakingNew); $i++)
    {
        $orderTakingNewCapitalKey = array();
        $orderTakingNew = $arrOrderTakingNew[$i];
        foreach ($orderTakingNew as $key => $value)
        {
            $orderTakingNewCapitalKey[makeFirstLetterUpperCase($key)] = $value;
        }
        array_push($arrOrderTakingNewCapitalKey,$orderTakingNewCapitalKey);
    }
    
    
    //make key capital letter for orderNote
    $arrOrderNoteNewCapitalKey = array();
    for($i=0; $i<sizeof($arrOrderNoteNew); $i++)
    {
        $orderNoteNewCapitalKey = array();
        $orderNoteNew = $arrOrderNoteNew[$i];
        foreach ($orderNoteNew as $key => $value)
        {
            $orderNoteNewCapitalKey[makeFirstLetterUpperCase($key)] = $value;
        }
        array_push($arrOrderNoteNewCapitalKey,$orderNoteNewCapitalKey);
    }
    
    
    $dataList = array();
    $dataList[] = $arrOrderTakingNewCapitalKey;
    $dataList[] = $arrOrderNoteNewCapitalKey;
    $dataList[] = $luckyDrawSpendSettingList;
    
    
    
    if($orderChanged)
    {
        $warningMsg = "รายการอาหารที่คุณสั่งมีการเปลี่ยนแปลงบางส่วน กรุณาตรวจทานรายการที่คุณสั่งอีกครั้งค่ะ";
        writeToLog("รายการอาหารที่สั่งมีการอัพเดต: $warningMsg, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
        
        
        /* execute multi query */
        $response = array('status' => '2', 'msg' => $warningMsg, 'tableName' => 'Order', dataJson => $dataList);
        echo json_encode($response);
        exit();
    }
    //--------------------------
    
    
    
    
    $sumSpecialPrice = 0;
    for($i=0; $i<sizeof($arrOrderTaking); $i++)
    {
        $sumSpecialPrice += $otSpecialPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i];
    }
    
    
    //voucher code validate/////////////
    if($voucherCode)
    {
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
        
        

        
        $warningMsg = $warningMsgList[0]["Text"];
        if($warningMsg)
        {
            writeToLog("voucher code invalid: $warningMsg, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
            $response = array('status' => '2', 'msg' => $warningMsg);
            echo json_encode($response);
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
            $warningMsg = "โค้ดที่ใส่ไม่สามารถใช้กับเมนูที่คุณเลือก";
            writeToLog("voucher code invalid: $warningMsg, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
            $response = array('status' => '2', 'msg' => $warningMsg);
            echo json_encode($response);
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
                                    $otDiscountValue[$i] = $promotion["DiscountAmount"];
                                }
                                else if($promotion["DiscountType"] == 2)//percent
                                {
                                    $discountEach = $promotion["DiscountAmount"] * 0.01 * ($otSpecialPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i]);
                                    $discountEach = round($discountEach * 10000) * 0.0001;
                                    $discountValue += $discountEach;
                                    $otDiscountValue[$i] = $discountEach;
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
                //discountType == 2
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
                                $discountEach = $promotion["DiscountAmount"] * 0.01 * ($otSpecialPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i]);
                                $discountEach = round($discountEach * 10000) * 0.0001;
                                $discountValue += $discountEach;
                                $otDiscountValue[$i] = $discountEach;
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
                    
                    
                    $totalDiscount;
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
                            $otDiscountValue[$i] = $otSpecialPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i];
                        }
                    }
                    
                    
                    $discountValue = $discountValue > $totalPriceGetDiscount?$totalPriceGetDiscount:$discountValue;
                    
                    
                    //make new proportion
                    for($i=0; $i<sizeof($arrOrderTaking); $i++)
                    {
                        if($otDiscountValue[$i] > 0)
                        {
                            $otDiscountValue[$i] = round($otDiscountValue[$i]/$totalPriceGetDiscount*10000)/10000*$discountValue;
                        }
                    }
                }
            }
            
            
            if($promotion["MoreDiscountToGo"] != -1)
            {
                $discountValue = $discountValue > $promotion["MoreDiscountToGo"]?$promotion["MoreDiscountToGo"]:$discountValue;
                
                
                //total discount of discounted items
                $totalDiscount = 0;
                for($i=0; $i<sizeof($arrOrderTaking); $i++)
                {
                    if($otDiscountValue[$i] > 0)
                    {
                        $totalDiscount += $otDiscountValue[$i];
                    }
                }
                
                for($i=0; $i<sizeof($arrOrderTaking); $i++)
                {
                    if($otDiscountValue[$i] > 0)
                    {
                        $otDiscountValue[$i] = round($otDiscountValue[$i]/$totalDiscount*10000)/10000*$discountValue;
                    }
                }
            }
            if($promotion["DiscountType"] == 1) //baht
            {
                $discountType = 1;
                $discountAmount = $discountValue;
            }
            else if($promotion["DiscountType"] == 2) //percent
            {
                $discountType = 2;
                $discountAmount = $promotion["DiscountAmount"];
            }
            
            
            
            //promo or reward
            if($typeList[0]["Text"] == 1)
            {
                $promotionID = $promotion["PromotionID"];
            }
            else if($typeList[0]["Text"] == 2)
            {
                $rewardRedemptionID = $promotion["RewardRedemptionID"];
                $promoCodeID = $promotion["PromoCodeID"];
            }
            
            
            
            //check sharedDiscountAmount
            {
                $shopDiscount = $discountValue * $promotion["ShopDiscount"] * 0.01;
                $shopDiscount = round($shopDiscount * 10000)/10000;
                $jummumDiscount = $discountValue - $shopDiscount;
                
                if($promotion["SharedDiscountType"] == 1)//shop set maxDiscount
                {
                    if($shopDiscount > $promotion["SharedDiscountAmountMax"])
                    {
                        $shopDiscount = $promotion["SharedDiscountAmountMax"];
                        $shopDiscount = round($shopDiscount * 10000)/10000;
                        $jummumDiscount = $discountValue - $shopDiscount;
                    }
                }
                else if($promotion["SharedDiscountType"] == 2)//jummum set maxDiscount
                {
                    if($jummumDiscount > $promotion["SharedDiscountAmountMax"])
                    {
                        $jummumDiscount = $promotion["SharedDiscountAmountMax"];
                        $jummumDiscount = round($jummumDiscount * 10000)/10000;
                        $shopDiscount = $discountValue - $jummumDiscount;
                    }
                }
            }
        }
    }
    
    
    //***********
    
    //end voucher code validate/////////////
    
    
    //calculate value
    $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $priceIncludeVat = $selectedRow[0]["PriceIncludeVat"];
    $vatPercent = $selectedRow[0]["PercentVat"];
    $serviceChargePercent = $selectedRow[0]["ServiceChargePercent"];

    //totalAmount
    $totalAmount = 0;
    for($i=0; $i<sizeof($arrOrderTaking); $i++)
    {
        $totalAmount += $otPrice[$i] + $otTakeAwayPrice[$i] + $otNotePrice[$i];
    }
    
    //specialPriceDiscount;
    $specialPriceDiscount = 0;
    for($i=0; $i<sizeof($arrOrderTaking); $i++)
    {
        $specialPriceDiscount += $otPrice[$i] - $otSpecialPrice[$i];
    }

    //price after discount
    $priceAfterDiscount = ($sumSpecialPrice - $discountValue);
    $priceAfterDiscount = round($priceAfterDiscount*10000)/10000;
    
    //price before vat
    $priceBeforeVat = $priceAfterDiscount;
    if($priceIncludeVat)
    {
        $priceBeforeVat = $priceAfterDiscount / (($vatPercent+100)*0.01);
        $priceBeforeVat = round($priceBeforeVat*10000)/10000;
    }
    
    //service charge
    $serviceChargeValue = $serviceChargePercent * $priceBeforeVat * 0.01;
    $serviceChargeValue = round($serviceChargeValue*10000)/10000;
    
    //total beforeVat
    $beforeVat = $priceBeforeVat+$serviceChargeValue;
    $beforeVat = round($beforeVat*10000)/10000;
    
    //vat
    $vatValue = ($priceBeforeVat+$serviceChargeValue)*$vatPercent * 0.01;
    $vatValue = round($vatValue*10000)/10000;
    
    //net total
    $creditCardAmount = $priceBeforeVat + $serviceChargeValue + $vatValue;
    $amount = round($creditCardAmount * 100);
    //---------------------------
    
    

    //has buffet menu
    //get dbName
    $sql = "select * from $jummumOM.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $dbName = $selectedRow[0]["DbName"];
    
    
    $hasBuffetMenu = 0;
    $timeToOrder = 0;
    for($i=0; $i<sizeof($arrOrderTaking); $i++)
    {
        $sql = "select * from $dbName.Menu where menuID = '$otMenuID[$i]'";
        $selectedRow = getSelectedRow($sql);
        $buffetMenu = $selectedRow[0]["BuffetMenu"];
        if($buffetMenu)
        {
            $hasBuffetMenu = 1;
            $timeToOrder = $selectedRow[0]["TimeToOrder"];
            break;
        }
    }
    
    
    
    
    //omise part
    if($amount != 0)
    {
        require_once  dirname(__FILE__) . '/../../omise-php/lib/Omise.php';
        
        
        $sql = "select * from Setting where keyName = 'PublicKey'";
        $selectedRow = getSelectedRow($sql);
        $publicKey = $selectedRow[0]["Value"];
        $sql = "select * from Setting where keyName = 'SecretKey'";
        $selectedRow = getSelectedRow($sql);
        $secretKey = $selectedRow[0]["Value"];
        define('OMISE_PUBLIC_KEY', "$publicKey");
        define('OMISE_SECRET_KEY', "$secretKey");
        
        
        try
        {
            $charge = OmiseCharge::create(array(
                                                'amount'   => $amount,
                                                'currency' => 'THB',
                                                'card'     => "$omiseToken"
                                                ));
            
        }
        catch (Exception $e)
        {
            writeToLog("omise charge fail, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
            $response = array('status' => '2', 'msg' => $e->getMessage());
            echo json_encode($response);
            exit();
        }
    }
    else
    {
        $doReceiptProcess = 1;
    }
    
    
    if($doReceiptProcess || $charge["status"] == "successful")//omise status
    {
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        
        
        // Set autocommit to off
        mysqli_autocommit($con,FALSE);
        writeToLog("set auto commit to off");
        
        
        
        //query statement
        $currentDateTime = date('Y-m-d');
        $sql = "select * from transactionFee where date_format(StartDate,'%Y-%m-%d') <= '$currentDateTime' and date_format(EndDate,'%Y-%m-%d') >= '$currentDateTime' and branchID = 0 order by modifiedDate desc";
        $selectedRow = getSelectedRow($sql);
        $transactionFee = $selectedRow[0]["Rate"];
        $transactionFeeValue = $amount * 0.01 * $transactionFee * 0.01;
        $transactionFeeValue = round($transactionFeeValue * 10000)/10000;
        $sql = "select * from transactionFee where date_format(StartDate,'%Y-%m-%d') <= '$currentDateTime' and date_format(EndDate,'%Y-%m-%d') >= '$currentDateTime' and branchID = '$branchID' order by modifiedDate desc";
        $selectedRow = getSelectedRow($sql);
        $transactionFeeBranch = $selectedRow[0]["Rate"];
        $transactionFeeValueBranch = $amount * 0.01 * $transactionFeeBranch * 0.01;
        $transactionFeeValueBranch = round($transactionFeeValueBranch * 10000)/10000;
        $jummumPayValue = $transactionFeeValue - $transactionFeeValueBranch;
         $sql = "INSERT INTO Receipt(BranchID, CustomerTableID, MemberID, ServingPerson, CustomerType, OpenTableDate, TotalAmount, CashAmount, CashReceive, CreditCardType, CreditCardNo, CreditCardAmount, TransferDate, TransferAmount, Remark, SpecialPriceDiscount, DiscountType, DiscountAmount, DiscountValue, DiscountReason, ServiceChargePercent, ServiceChargeValue, PriceIncludeVat, VatPercent, VatValue, BeforeVat, Status, StatusRoute, ReceiptNoID, ReceiptNoTaxID, ReceiptDate, SendToKitchenDate, DeliveredDate, MergeReceiptID, HasBuffetMenu, TimeToOrder, BuffetEnded, BuffetEndedDate, BuffetReceiptID, VoucherCode, ShopDiscount, JummumDiscount, TransactionFeeValue, JummumPayValue, ModifiedUser, ModifiedDate) VALUES ('$branchID', '$customerTableID', '$memberID', '$servingPerson', '$customerType', '$openTableDate', '$totalAmount', '$cashAmount', '$cashReceive', '$creditCardType', '$creditCardNo', '$creditCardAmount', '$transferDate', '$transferAmount', '$remark', '$specialPriceDiscount', '$discountType', '$discountAmount', '$discountValue', '$discountReason', '$serviceChargePercent', '$serviceChargeValue', '$priceIncludeVat', '$vatPercent', '$vatValue', '$beforeVat', '$status', '$status', '$receiptNoID', '$receiptNoTaxID', '$receiptDate', '$sendToKitchenDate', '$deliveredDate', '$mergeReceiptID', '$hasBuffetMenu', '$timeToOrder', '$buffetEnded', '$buffetEndedDate', '$buffetReceiptID', '$voucherCode', '$shopDiscount', '$jummumDiscount', '$transactionFeeValue', '$jummumPayValue', '$modifiedUser', '$modifiedDate')";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
//            putAlertToDevice();
            echo json_encode($ret);
            exit();
        }
        
        
        
        //insert ผ่าน
        $newID = mysqli_insert_id($con);
        
        
        
        
        //update receiptNoID and
        //select row ที่แก้ไข ขึ้นมาเก็บไว้
        $receiptID = $newID;
        $receiptNoID = luhnAlgorithm(sprintf("%06d", $receiptID));
        $sql = "update Receipt set ReceiptNoID = '$receiptNoID' where ReceiptID = '$receiptID'";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
//            putAlertToDevice();
            echo json_encode($ret);
            exit();
        }

        
        $sql = "select * from Receipt where ReceiptID = '$receiptID';";
        $sqlAll = $sql;
        //-----
        
        
        
        
        //orderTakingList
        $orderTakingOldNew = array();
        if(sizeof($arrOrderTaking) > 0)
        {
            for($k=0; $k<sizeof($arrOrderTaking); $k++)
            {
                //query statement
                $sql = "INSERT INTO OrderTaking(BranchID, CustomerTableID, MenuID, Quantity, SpecialPrice, Price, TakeAway, TakeAwayPrice, NoteIDListInText, NotePrice, DiscountValue, OrderNo, Status, ReceiptID, ModifiedUser, ModifiedDate) VALUES ('$otBranchID[$k]', '$otCustomerTableID[$k]', '$otMenuID[$k]', '$otQuantity[$k]', '$otSpecialPrice[$k]', '$otPrice[$k]', '$otTakeAway[$k]', '$otTakeAwayPrice[$k]', '$otNoteIDListInText[$k]', '$otNotePrice[$k]', '$otDiscountValue[$k]', '$otOrderNo[$k]', '$otStatus[$k]', '$receiptID', '$otModifiedUser[$k]', '$otModifiedDate[$k]')";
                $ret = doQueryTask($sql);
                if($ret != "")
                {
                    mysqli_rollback($con);
                    //                    putAlertToDevice();
                    echo json_encode($ret);
                    exit();
                }
                
                
                
                //insert ผ่าน
                $newID = mysqli_insert_id($con);
                
                
                
                
                //select row ที่แก้ไข ขึ้นมาเก็บไว้
                $orderTakingOldNew[$otOrderTakingID[$k]] = $newID;
                $otOrderTakingID[$k] = $newID;
            }
            
            
            
            //**********sync device token อื่น
            //select row ที่แก้ไข ขึ้นมาเก็บไว้
            $sql = "select * from OrderTaking where OrderTakingID in ('$otOrderTakingID[0]'";
            for($i=1; $i<sizeof($arrOrderTaking); $i++)
            {
                $sql .= ",'$otOrderTakingID[$i]'";
            }
            $sql .= ");";
            $sqlAll .= $sql;
        }
        //-----
        
        
        
        //orderNoteList
        if(sizeof($arrOrderNote) > 0)
        {
            for($k=0; $k<sizeof($arrOrderNote); $k++)
            {
                //query statement
                $onOrderTakingID[$k] = $orderTakingOldNew[$onOrderTakingID[$k]];
                $sql = "INSERT INTO OrderNote(OrderTakingID, NoteID, Quantity, ModifiedUser, ModifiedDate) VALUES ('$onOrderTakingID[$k]', '$onNoteID[$k]', '$onQuantity[$k]', '$onModifiedUser[$k]', '$onModifiedDate[$k]')";
                $ret = doQueryTask($sql);
                if($ret != "")
                {
                    mysqli_rollback($con);
                    //                    putAlertToDevice();
                    echo json_encode($ret);
                    exit();
                }
                
                
                
                //insert ผ่าน
                $newID = mysqli_insert_id($con);
                
                
                
                //select row ที่แก้ไข ขึ้นมาเก็บไว้
                $onOrderNoteID[$k] = $newID;
            }
            
            
            
            //**********sync device token อื่น
            //select row ที่แก้ไข ขึ้นมาเก็บไว้
            $sql = "select * from OrderNote where OrderNoteID in ('$onOrderNoteID[0]'";
            for($i=1; $i<sizeof($arrOrderNote); $i++)
            {
                $sql .= ",'$onOrderNoteID[$i]'";
            }
            $sql .= ");";
            $sqlAll .= $sql;
        }
        else
        {
            $sql = "select * from orderNote where 0;";
            $sqlAll .= $sql;
        }
        //------
        
        
        //lucky draw
        $sql = "select * from $selectedDbName.setting where keyName = 'luckyDrawSpend'";
        $selectedRow = getSelectedRow($sql);
        $luckyDrawSpend = $selectedRow[0]["Value"];
        if($luckyDrawSpend)
        {
            $luckyDrawTimes = floor($amount/100/$luckyDrawSpend);
        }
        else
        {
            $luckyDrawTimes = 0;
        }
        writeToLog("luckyDrawTimes: " . $luckyDrawTimes);
        if($luckyDrawTimes > 0)
        {
            for($i=0; $i<$luckyDrawTimes; $i++)
            {
                if($i==0)
                {
                    $sql = "insert into LuckyDrawTicket (ReceiptID,MemberID, RewardRedemptionID,GetTicketDate,ModifiedUser,ModifiedDate) values ('$receiptID','$memberID',-1,'$modifiedDate','$modifiedUser','$modifiedDate')";
                }
                else
                {
                    $sql .= ",('$receiptID','$memberID',-1,'$modifiedDate','$modifiedUser','$modifiedDate')";
                }
            }
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
                //                    putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
        }

        $currentDateTime = date('Y-m-d H:i:s');
        $sql = "select * from setting where keyName = 'LuckyDrawTimeLimit';";
        $selectedRow = getSelectedRow($sql);
        $luckyDrawTimeLimit = $selectedRow[0]["Value"];
        $sql = "select * from luckyDrawTicket left join receipt on luckyDrawTicket.receiptID = receipt.receiptID where luckyDrawTicket.memberID = '$memberID' and receipt.branchID = '$branchID' and rewardRedemptionID = -1 and TIME_TO_SEC(timediff('$currentDateTime', luckyDrawTicket.modifiedDate)) <= '$luckyDrawTimeLimit';";
        $sqlAll .= $sql;
        
        
        
        /* execute multi query */
        $dataJson = executeMultiQueryArray($sqlAll);
        
        
        
        if($promotionID)
        {
            $sql = "INSERT INTO UserPromotionUsed(UserAccountID, PromotionID, ReceiptID, ModifiedUser, ModifiedDate) VALUES ('$userAccountID', '$promotionID', '$receiptID', '$modifiedUser', '$modifiedDate')";
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
                //                    putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
        }
        if($rewardRedemptionID)
        {
            //query statement
            $sql = "INSERT INTO UserRewardRedemptionUsed(UserAccountID, RewardRedemptionID, ReceiptID, ModifiedUser, ModifiedDate) VALUES ('$userAccountID', '$rewardRedemptionID', '$receiptID', '$modifiedUser', '$modifiedDate')";
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
                //                    putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
            
            
            
            //query statement
            $sql = "update promoCode set status = 2, modifiedUser = '$modifiedUser', modifiedDate = '$modifiedDate' where promoCodeID = '$promoCodeID'";
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
                //                    putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
        }
        

        
        
        
        //reward
        $sql = "SELECT * FROM `rewardprogram` WHERE StartDate <= now() and EndDate >= now() and type = 1 order by modifiedDate desc";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)>0)
        {
            $salesSpent = $selectedRow[0]["SalesSpent"];
            $receivePoint = $selectedRow[0]["ReceivePoint"];
            $rewardPoint = $amount/100.0/$salesSpent*$receivePoint;
            
            
            $sql = "INSERT INTO `rewardpoint`(`MemberID`, `ReceiptID`, `Point`, `Status`, `ModifiedUser`, `ModifiedDate`) VALUES ('$memberID','$receiptID','$rewardPoint',1,'$modifiedUser','$modifiedDate')";
            $sql = "INSERT INTO RewardPoint(MemberID, ReceiptID, Point, Status, PromoCodeID, ModifiedUser, ModifiedDate) VALUES ('$memberID', '$receiptID', '$rewardPoint', '1', '0', '$modifiedUser', '$modifiedDate')";
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
//                putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
        }
        //-----********
        //-----****************************
        //****************send noti to shop (turn on light)
        //alarmShop
        //query statement
        $ledStatus = 1;
        $sql = "update $jummumOM.Branch set LedStatus = '$ledStatus', ModifiedUser = '$modifiedUser', ModifiedDate = '$modifiedDate' where branchID = '$branchID';";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
            //        putAlertToDevice();
            echo json_encode($ret);
            exit();
        }
        mysqli_commit($con);
        //****************
        
        
        
        
        
        
        //get pushSync Device in JUMMUM OM
        $pushSyncDeviceTokenReceiveOrder = array();
        $sql = "select * from $jummumOM.device left join $jummumOM.Branch on $jummumOM.device.DbName = $jummumOM.Branch.DbName where branchID = '$branchID';";
        $selectedRow = getSelectedRow($sql);
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $deviceToken = $selectedRow[$i]["DeviceToken"];
            array_push($pushSyncDeviceTokenReceiveOrder,$deviceToken);
        }
        //-----****************************
        


        $msg = 'New order coming!! order no:' . $receiptNoID;
        $category = "printKitchenBill";
        $contentAvailable = 1;
        $data = array("receiptID" => $receiptID);
        sendPushNotificationJummumOM($pushSyncDeviceTokenReceiveOrder,$title,$msg,$category,$contentAvailable,$data);

        
        
        
        
        
        
        //do script successful
//        mysqli_commit($con);
        mysqli_close($con);
        
        
        
        
        writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
        $response = array('status' => '1', 'sql' => $sql, 'tableName' => 'OmiseCheckOut', dataJson => $dataJson);
        
        echo json_encode($response);
        
        exit();
    }
    else
    {
        writeToLog("omise charge fail, file: " . basename(__FILE__) . ", user: " . $data['modifiedUser']);
        $response = array('status' => '2', 'msg' => 'ตัดบัตรเครดิตไม่สำเร็จ กรุณาตรวจสอบข้อมูลบัตรเครดิตใหม่อีกครั้ง');
        echo json_encode($response);
        exit();
    }


?>
