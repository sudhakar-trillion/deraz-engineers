<?php include("../includes/db.php");
  $getDetails = mysql_query("select department from customers where customerId = '". $_GET['cid'] ."'");
  $getDetails = mysql_fetch_array($getDetails);
  ?>
   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="department"> Department </label>

										<div class="col-sm-9">
                                        	<select id="department" name="department" placeholder="Contact Person" class="col-xs-10 col-sm-12">
                                            	<option value="<?php echo $getDetails['department']; ?>"><?php echo $getDetails['department']; ?></option>
                                            </select>
										</div>
									</div>