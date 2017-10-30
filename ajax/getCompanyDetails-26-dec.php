<?php include("../includes/db.php");
  $getDetails = mysql_query("select contactPerson, designation, email, phone from customers where customerId = '". $_GET['cid'] ."'");
  $getDetails = mysql_fetch_array($getDetails);

  ?>
   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

										<div class="col-sm-9">
		<input type="text" id="contactPerson" name="contactPerson" placeholder="Contact Person" class="col-xs-10 col-sm-12" value="<?php echo $getDetails['contactPerson'] ?>" />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

										<div class="col-sm-9">
	<input type="text" id="designation" name="designation" placeholder="Designation" class="col-xs-10 col-sm-12" value="<?php echo $getDetails['designation'] ?>"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

										<div class="col-sm-9">
			<input type="text" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12" value="<?php echo $getDetails['email'] ?>"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

										<div class="col-sm-9">
	<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12" value="<?php echo $getDetails['phone'] ?>"  />
										</div>
									</div>
