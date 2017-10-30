<?php include("../includes/db.php");

$categories = mysql_query("select id, category from categories order by category");

?> <div class="col-xs-12 form-group" id="<?php echo 'pr'.$_GET['id']; ?>">    
                                
                                
                                <div class="col-sm-3">
                                
                                </div>
                                <div class="col-sm-2">
                                <span>Category</span>
                                <select id="<?php echo 'category'.$_GET['id'] ?>" name="categories[]" class="col-xs-10 col-sm-12" onChange="getBrands('<?php echo $_GET['id']; ?>')">
                                            <option value="0">Select Category</option>
                                            <?php
											while($category = mysql_fetch_array($categories))
											{	?> <option value="<?php echo $category['id'] ?>"><?php echo $category['category'] ?></option><?php } ?>
                                            </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="<?php echo 'brand'.$_GET['id'] ?>" name="brands[]" class="col-xs-10 col-sm-12" onChange="getProducts('<?php echo $_GET['id']; ?>')">
                                            <option value="0">Select Brand</option>
                                            </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Product</span>
             <select name="products[]" id="<?php echo 'product'.$_GET['id'] ?>" class="col-xs-10 col-sm-12" onchange="getModels('<?php echo $_GET['id']; ?>')">
                                            <option value="0">Select Product</option>
                                            </select>
                                </div>
                                
                                 <div class="col-sm-2">
                                <span>Model No</span>
             <select name="models[]" id="<?php echo 'model'.$_GET['id'] ?>" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Model</option>
                                            </select>
                                </div> 
                                
                                 <div id="<?php echo 'productdetails'.$_GET['id'] ?>"> 
                                 <div class="col-sm-1">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" id="<?php echo 'quantity'.$_GET['id'] ?>" value="" size="10" onkeyup="getPrice('<?php echo $_GET['id'] ?>')" />
                                </div> 
                                 
                              
                                </div>
                                
                                 
                                </div>  