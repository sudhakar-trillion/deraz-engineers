<?php include("includes/header.php"); 

		if(isset($_POST['update']))
		{
	   mysql_query("update teams set  teamLeaderId = '". $_POST['teamLeader'] ."'");			
	  // mysql_query("insert into teams (`teamLeaderId`, `dateTime`) values ('". $_POST['teamLeader'] ."',  NOW())");			
       
					$leader = mysql_query("select employees.firstName from teams
		left join employees on teams.teamLeaderId = employees.id
		
		 where teams.teamId = '". $_GET['tid'] ."'");
			if(mysql_num_rows($leader)) {
			$leader = mysql_fetch_array($leader);
			}
			
				$count = count($_POST['selectedMembers']);
				for($i=0;$i<$count;$i++)
				{
				
mysql_query("insert into team_members (`teamId`, `memberId`, `dateTime`) values ('". $_GET['tid'] ."', '". $_POST['selectedMembers'][$i] ."', NOW())");
			  
			$member = mysql_query("select firstName, email from employees where id = '". $_POST['selectedMembers'][$i] ."'");
			if(mysql_num_rows($member)) {
			$member = mysql_fetch_array($member);
			// 
			$subject = 'Team Creation';
			
			$headers = "From: " . strip_tags('no-reply@derazengineers.com') . "\r\n";
			$headers .= "Reply-To: ". strip_tags('no-reply@derazengineers.com') . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$message = "Hi ".$member['firstName'].",
						You have been assigned as a team member to ".$leader['firstName']."'s Team at Trismart.";
			
			mail($member['email'],$subject,$message,$headers);	
			
			
				}
				}
				
			  header("location: teams.php?tid=".$_GET['tid']."&update=1");	
			/*}
			else
			{
			  header("location: add_team.php?error=1");	
			}*/
			
		}

 $roles = mysql_query("select * from rolls where roll_Id > '1'");
 
 //
 
 
 $result  = mysql_query("select employees.firstName, team_members.memberId from team_members 
 left join employees on team_members.memberId = employees.id
 where team_members.teamId= '" .$_GET['tid'] . "'");
 
 
  

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="teams.php">Teams</a>
							</li>

							<li class="active">Add Employee</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							



								<div class="row">
									<div class="col-xs-6">
										
										<div class="table-header">
											Edit Team
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Team has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  $sales = mysql_query("select id, firstName from employees where roll = '4' and role = '1'"); ?> 
                                    <form class="form-horizontal row-border" id="validation-form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                
                                   <div class="form-group">
										<label for="firstName" class="col-md-4 control-label">Team Leader<span class="required">*</span></label>
                                        <div class="col-md-8">
                                        <div class="clearfix">
                                    		<select name="teamLeader" id="teamLeader" class="form-control required">
                                            <?php 
											
											while($sale = mysql_fetch_array($sales))
											{
											
								?><option value="<?php echo $sale['id']; ?>"><?php echo $sale['firstName']; ?></option><?php			
											 	
											}
											
											?>
                                            
                                            </select>
                                            </div>
										</div>
                                        </div>
                                        
                                     
                                      <div class="form-group">   
                                        <label for="fatherName" class="col-md-4 control-label">Team Member's <span class="required">*</span></label>
                                        <div class="col-md-8"> 
                                         <div class="clearfix">
											<input type="text" name="teamMembers[]" id="teamMembers" onKeyUp="getSalesList(this.value)" class="form-control" >
                                            <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none;" id="salesList"></ul>
                                            <div id="membersSelected">
                                            
              
                                            <?php
                                             while($row = mysql_fetch_array($result))
 {
	                                 ?>
                                     
               <p id="<?php  echo 'mid'.$row['memberId']; ?>">
                 <a href="javascript:void()" onclick="removeExistingMember('<?php echo $_GET['tid'] ?>','<?php  echo $row['memberId']; ?>','<?php  echo $row['firstName']; ?>')">
                    <i class="ace-icon fa fa-trash icon-only"></i>
                </a>
               <?php  echo $row['firstName']; ?></p>
                                     <?php        
	 
 } ?>

                                            
                                            </div>
                                            
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                <div class="clearfix form-actions">
										<div class="col-md-offset-6 col-md-6">
											<button class="btn btn-info" type="submit" name="update">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Update
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>

                                    
                                    </form>
                                    
										</div>
									</div>
								</div>

								
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="">
							<span class="orange bolder"><img src="assets/images/smarterp.png" width="90"  /></span>

 <a href="http://www.trillionit.com/" target="_blank">Trillion IT</a> &copy; 2017
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.js"></script>


		<!-- page specific plugin scripts -->
		<script src="assets/js/jquery.dataTables.js"></script>
		<script src="assets/js/jquery.dataTables.bootstrap.js"></script>
        
        
         <script src="assets/js/jquery.validate.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace/elements.scroller.js"></script>
		<script src="assets/js/ace/elements.colorpicker.js"></script>
		<script src="assets/js/ace/elements.fileinput.js"></script>
		<script src="assets/js/ace/elements.typeahead.js"></script>
		<script src="assets/js/ace/elements.wysiwyg.js"></script>
		<script src="assets/js/ace/elements.spinner.js"></script>
		<script src="assets/js/ace/elements.treeview.js"></script>
		<script src="assets/js/ace/elements.wizard.js"></script>
		<script src="assets/js/ace/elements.aside.js"></script>
		<script src="assets/js/ace/ace.js"></script>
		<script src="assets/js/ace/ace.ajax-content.js"></script>
		<script src="assets/js/ace/ace.touch-drag.js"></script>
		<script src="assets/js/ace/ace.sidebar.js"></script>
		<script src="assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="assets/js/ace/ace.submenu-hover.js"></script>
		<script src="assets/js/ace/ace.widget-box.js"></script>
		<script src="assets/js/ace/ace.settings.js"></script>
		<script src="assets/js/ace/ace.settings-rtl.js"></script>
		<script src="assets/js/ace/ace.settings-skin.js"></script>
		<script src="assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="assets/js/ace/ace.searchbox-autocomplete.js"></script>
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>
        
       
		
		<script type="text/javascript">
		function getSalesList(val)
		{
			
			document.getElementById("salesList").style.display = 'block';
				$.ajax({url: "ajax/getSalesList.php?val="+val, success: function(result){
		
		
       $("#salesList").html(result);
    }});	
			
		}
		
		
		function selectMember(id,val)
		{
			
			
			document.getElementById("salesList").style.display = 'none';
			
			document.getElementById("teamMembers").value = '';
			
			data = '<p id="mid'+id+'"><a href="javascript:void()" onclick="removeMember('+id+')"><i class="ace-icon fa fa-trash icon-only"></i></a><input type="hidden" name="selectedMembers[]" value="'+id+'" />'+val+'</p>';
			
			$("#membersSelected").append(data);
			
			
		
		}
		
		
		function removeMember(rid)
		{
		    $("#mid"+rid).remove();
		}
		
		
		
		
		function removeExistingMember(tid,mid,name)
		{
		
		
		$.ajax({url: "ajax/removeTeamMember.php?tid="+tid+"&mid="+mid+"&name="+name, success: function(result){
		  $("#mid"+mid).html(result);
       }});	
		     //$("#mid"+rid).remove();
		}
		
			</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				var oTable1 = 
				$('#sample-table-2')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.dataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null, null,null, null, null,
					  { "bSortable": false }
					],
					"aaSorting": [],
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
			    } );
				/**
				var tableTools = new $.fn.dataTable.TableTools( oTable1, {
					"sSwfPath": "../../copy_csv_xls_pdf.swf",
			        "buttons": [
			            "copy",
			            "csv",
			            "xls",
						"pdf",
			            "print"
			        ]
			    } );
			    $( tableTools.fnContainer() ).insertBefore('#sample-table-2');
				*/
				
				
				//oTable1.fnAdjustColumnSizing();
			
			
				$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
				});
			
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
			
			// datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					 format: 'dd-mm-yyyy'
				})
				
				
				$('.date-picker1').datepicker({
					//autoclose: true,
					//format: 'dd-mm-yyyy'
					format: "dd-mm-yyyy",
             startView: "year", 
    minViewMode: "date"
   // minViewMode: "date"
				})
				
				
			
			// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						firstName: {
							required: true,
							
						},
						fatherName: {
							required: true,
							
						},
						dateBirth: {
							required: true,
							
						},						
						bloodGroup: {
							required: true,
							
						},
						mobile: {
							required: true,
							
						},
						email: {
							required: true,
							
						},
						officialEmail: {
							required: true,
							
						},
						emergencyContact: {
							required: true,
							
						},
						emergencyName: {
							required: true,
							
						},
						relation: {
							required: true,
							
						},
						c_hno: {
							required: true,
							
						},
						c_street: {
							required: true,
							
						},
						c_landmark: {
							required: true,
							
						},
						c_city: {
							required: true,
							
						},
						c_area: {
							required: true,
							
						},
						c_pincode: {
							required: true,
							
						},
						p_hno: {
							required: true,
							
						},
						p_street: {
							required: true,
							
						},
						p_landmark: {
							required: true,
							
						},
						p_city: {
							required: true,
							
						},
						p_area: {
							required: true,
							
						},
						p_pincode: {
							required: true,
							
						},
						roll: {
							required: true,
							
						},
						designation: {
							required: true,
							
						},
						username2: {
							required: true,
							
						},
						password3: {
							required: true,
							
						},
						dateJoining: {
							required: true,
							
						}
					},
			    	highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {   
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					}
			
				
				});
				// validation
			
			})
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="assets/js/ace/elements.onpage-help.js"></script>
		<script src="assets/js/ace/ace.onpage-help.js"></script>
		<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>
	</body>
</html>

								