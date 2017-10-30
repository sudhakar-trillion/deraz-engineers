<?php include("../includes/db.php");

$departments = mysql_query("select departments.deptId, departments.department from company_departments left join departments on company_departments.deptId = departments.deptId
   where company_departments.autoId = '". $_GET['cid'] ."' order by departments.department");

?>
<option value="0">Select Departments</option>
<?php
											while($department = mysql_fetch_array($departments))
											{	?> <option value="<?php echo $department['deptId'] ?>"><?php echo $department['department'] ?></option><?php } ?>