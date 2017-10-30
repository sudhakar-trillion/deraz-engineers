<!--getFinancialTargetDetails-->
<?php
require('../includes/db.php');


  $currentYear = date('Y');
											$previousYear =	$currentYear-1;
										    $nextYear =	$currentYear+1;
											
											$currentMonth = date('m');
											
											
											if($currentMonth>3)
											{
											  $financialYear = 	$currentYear.'-'.$nextYear;
											}
											else
											{
											  $financialYear = 	$currentYear.'-'.$previousYear;
											}
										
										
											
	//$financialYear										
	
	$targetMonths = mysql_query("select financialMonth, target from targets where employeeId = '". $_GET['eid'] ."' and financialYear = '". $_GET['finYear'] ."'");				
	
if(mysql_num_rows($targetMonths)>0)
	{
		while($targetMonth = mysql_fetch_array($targetMonths))
		{
		  	$monthData[] = $targetMonth['financialMonth'];
			$targetData[] = $targetMonth['target'];
		}
		
	}
	else
	{
	  $monthData[] ='';	 $targetData[] = '';
	}
	
	 ?>
                                            
                                    <form class="form-horizontal" method="post" action="">         
                                            
                                            <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Financial Year </label>

										<div class="col-sm-9">
		<select  id="financialYear" name="financialYear" class="col-xs-10 col-sm-5" onchange="getFinancialTargetDetails('<?php echo $_GET['eid'] ?>',this.value)">
                                        <?php for($i=2015;$i<=2020;$i++)
											{  
											
											$n = $i+1
	 ?><option value="<?php echo $i.'-'.$n; ?>" <?php if(strcmp($i.'-'.$n,$_GET['finYear'])==0) { ?> selected="selected" <?php } ?>><?php echo $i.' to '.$n; ?></option>	
											<?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="month"> Month </label>

										<div class="col-sm-9">
                                        
											<input type="hidden" name="employeeId"  value="<?php echo $_GET['eid'] ?>" />
                                            <select id="month"  name="month" class="col-xs-10 col-sm-5">
                                            <option value="">Select Month</option>
 <?php if(!(in_array('04',$monthData))) { ?><option value="04">April</option><?php } ?>
 <?php if(!(in_array('05',$monthData))) { ?><option value="05">May</option><?php } ?>
 <?php if(!(in_array('06',$monthData))) { ?><option value="06">June</option><?php } ?>
 <?php if(!(in_array('07',$monthData))) { ?> <option value="07">July</option><?php } ?>
 <?php if(!(in_array('08',$monthData))) { ?> <option value="08">August</option><?php } ?>
 <?php if(!(in_array('09',$monthData))) { ?>  <option value="09">November</option><?php } ?>
 <?php if(!(in_array('10',$monthData))) { ?> <option value="10">October</option><?php } ?>
 <?php if(!(in_array('11',$monthData))) { ?> <option value="11">November</option><?php } ?>
 <?php if(!(in_array('12',$monthData))) { ?> <option value="12">December</option><?php } ?>
 <?php if(!(in_array('01',$monthData))) { ?> <option value="01">January</option><?php } ?>
 <?php if(!(in_array('02',$monthData))) { ?>  <option value="02">February</option><?php } ?>
 <?php if(!(in_array('03',$monthData))) { ?>     <option value="03">March</option><?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="target"> Target </label>

										<div class="col-sm-9">
                                            <input type="text" id="target"  name="target" class="col-xs-10 col-sm-5" value="">
                                         
										</div>
									</div>
                                    
                                  
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

										<div class="col-sm-9">
											<button type="submit" name="addTarget" class="col-xs-10 col-sm-5 btn btn-sm btn-info">
                                            Submit
                                            </button>
										</div>
									</div>
                                    </form>
                                    
                                    <?php
						
	$targets = mysql_query("select financialMonth, financialYear, target from targets where employeeId = '". $_GET['eid'] ."' and financialYear = '". $_GET['finYear'] ."'");	
	
	
	    $monthName[1] = 'January';
		$monthName[2] = 'February';
		$monthName[3] = 'March';
		$monthName[4] = 'April';
		$monthName[5] = 'May';
		$monthName[6] = 'June';
		$monthName[7] = 'July';
		$monthName[8] = 'August';
		$monthName[9] = 'September';
		$monthName[10] = 'October';
		$monthName[11] = 'November';
		$monthName[12] = 'December';
		
		
		
		//	$targetMonths = mysql_query("select financialMonth, target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $financialYear ."'");							
									
									?>
                                    
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
															<th>Financialy year</th>
                                                            <th>Month</th>
															<th>Target</th>
                                                            
														</tr>
													</thead>

													<tbody>
														

								<?php 
								
								
								
								if(mysql_num_rows($targets)>0) { $num = 1;
								while($target = mysql_fetch_array($targets))
								{ ?>						

														<tr>
															<td>
															<?php echo $num; ?>
															</td>
															<td><?php echo $target['financialYear']; ?></td>
                                                            <td><?php 
															
															if($target['financialMonth']<10)
															{
															
															 $monthId = $target['financialMonth'][1];
															echo $monthName[$monthId]; 	
															}
															else
															{
															
															echo $monthName[$target['financialMonth']]; 
															}
															
															
															 ?></td>
															<td><?php echo $target['target']; ?></td>
															
														</tr>
                                                        <?php $num++; } }  else { ?> <tr><td colspan="3">No Data found.</td></tr> <?php } ?>
													</tbody>
												</table>
                                                
                                                
                                                
                                                <?php unset($monthData); unset($targetData);  ?>
									