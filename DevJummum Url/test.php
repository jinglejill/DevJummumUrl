<?php
    and (
    promotion.NoOfLimitUsePerUser = 0 or
    promotion.NoOfLimitUsePerUser > (select count(*) from userPromotionUsed where promotionID = promotion.promotionID and userAccountID = '$memberID')
    )
?>




