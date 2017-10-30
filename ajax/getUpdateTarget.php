<?php require('../includes/db.php'); ?>
<form action="" method="post">
<input type="text" name="updateTargetValue" value="<?php echo $_GET['target']; ?>"  />
<input type="hidden" name="tid" value="<?php echo $_GET['tid']; ?>" />
<input type="submit" name="updateTarget" value="GO" />
</form>
