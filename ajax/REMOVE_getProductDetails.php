<?php include("../includes/db.php");

$currentDate = date('Y-m-d');

$prices = mysql_query("select `price` from product_price where productId = '". $_GET['pid'] ."' and fromDate <= '$currentDate' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);


$amount = $price['price']*1;

?>

                               <div class="col-sm-2">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" id="<?php echo 'quantity'.$_GET['rid'] ?>" value="1" onkeyup="getPrice('<?php echo $_GET['rid'] ?>')"  />
                                </div> 
                                 <div class="col-sm-2">
                                <span>Price</span>
                                <input type="text" name="price[]" id="<?php echo 'price'.$_GET['rid'] ?>" value="<?php echo $price['price']; ?>" onkeyup="getPrice('<?php echo $_GET['rid'] ?>')"  />
                                </div> 
                                 <div class="col-sm-2">
                                <span>Amount</span>
                                <input type="text" name="amount[]" id="<?php echo 'amount'.$_GET['rid'] ?>" value="<?php echo $amount; ?>"   />
                                </div> 