<!DOCTYPE html> <html>
<head>
<title>GB Prime Pay</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<div>Merchant Payment</div> <div>
<form action="https://api.gbprimepay.com/gbp/gateway/qrcode" method="post">
<div>
<label>Token: </label>
<input type="text" name="token" value="M+9Y1b+zsE2TgkdBKBy0BpvyspOumyVhgzKXzhdKxtFpDjR/LRuD6w3DdoXjL5d4Xk7Omgh1M89lXdiTNOntb0OVlvYGcdR9mqmIltu8miOitcWIkNyz6/wuAnHtwk0JN2aDEbdX/Wv0LRg3/Tn1AK5inb0=" />
</div> <div>
<label>Amount: </label>
<input type="text" name="amount" value="2.00" />
</div> <div>
<label>Response URL: </label>
<input type="text" name="responseUrl" value="https://jummum.co/app/webqr.php" /> </div>
<div>
<label>Detail: </label>
<input type="text" name="detail" value="money" />
</div>
<div>
<label>Reference No: </label>
<input type="text" name="referenceNo" value="201811291219" />
</div> <div>
<label>Pay Type: </label>
<input type="radio" name="payType" value="F" checked="checked" /> Full <input type="radio" name="payType" value="I" /> Installment
</div> <div>
<button type="submit">Pay</button> </div>
<div>
<label>Background URL: </label>
<input type="text" name="backgroundUrl" value="https://jummum.co/app/background_qr.php" /> </div> </form>
</div>
</body> </html>
