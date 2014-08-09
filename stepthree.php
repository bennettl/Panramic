<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();
require_once(CLASS_DIR.'field.php');

if (isset($_POST['finish'])){
	if (isset($_POST['field'])){
		$field_ids = $_POST['field'];
		$current_user->postFields($field_ids);		
	}
	// Redirect user to homepage regardless of whether or not they choosen a field
	$home = '/?first=1';
	header('Location:'. $home);
	exit;
}
$content = '<link rel="stylesheet" type="text/css" href="css/stepthree.css" />
			<script type="text/javascript" src="js/stepthree.js"></script>';
get_header(array('noHeader' =>  true, 'content' => $content));
?>
<div id="fieldBg"></div>
<div id="container">
	<div id="topContainer">
     	<p id="fieldsText">Fields</p>
        <div id="description">
           <p> Base on the <strong>fields</strong> you are interested in, you will immediately know of any events related to those fields. Don't be afaird to explore your interests! You can get always change them down the line.</p>
        </div>
	</div>
	<div id="bottomContainer">
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
        	<input type="submit" name="finish" id="finishBtn" value="Finish" />
        </form>
	</div> 
<!-- #container -->
</div>
</body>
</html>