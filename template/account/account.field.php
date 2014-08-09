<?php
require_once('connect.php'); // Sets up db connection, user session, etc.
redirect_logged_out_users();
require_once(CLASS_DIR.'user.php');
require_once(CLASS_DIR.'field.php');
// Setup the user
$current_user->setConnections('field');
?>
<div id="field">   
    <div id="add" style="width:820px;">
        <div class="settingsTitle">Add</div>
        <div class="titleDivider"></div>
        <p class="fieldNoti noti"></p>
        <ul id="fieldContainer">
        	<?php
        	// Loop through all fields
			foreach (Field::get_all_fields() as $field => $fieldId){
				if ($fieldId != 15){
					echo '<li id="'.$fieldId.'"><img src="css/images/fields/'.$fieldId.'.png" /><div class="fieldtitle">'.$field.'</div><div class="overlay"></div></li>';
				}
			}
			?>
        </ul> 
         <form id="addField" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="submit" class="inputSubmit settingsSubmit" name="addField" value="Add" />
         </form>
    <!-- #add -->
    </div>
    <div id="remove">
        <div class="settingsTitle">Remove</div>
        <div class="titleDivider"></div>
		<?php if (count($current_user->fields) != 0): // If this user has fields, show them ?>
			<p class="fieldNoti noti"></p>
			<form id="removeField" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<table>
				<?php
				// Loop through user fields and display them
				foreach ($current_user->fields as $field => $fieldId){				
					echo '<tr><td><input type="checkbox" name="field[]" value='.$fieldId.' /><label>'.$field.'</label></td><td></tr>';
				}
				?>
				<tr><td style="width:820px;"><input type="submit" class="inputSubmit settingsSubmit" name="removeField" value="Remove" /></td></tr>
			</table>
			</form>
		<?php else: ?>
			<p class="noti pageNoti">There are currently no fields to remove</p>
		<?php endif; ?>		
    <!-- #remove -->
    </div>
<!-- #field -->   
</div>