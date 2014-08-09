<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

$content =  '<link rel="stylesheet" type="text/css" href="css/misc.css" />
             <script type="text/javascript" src="js/mail.js"></script>';
get_header(array('title' => 'Contact', 'content' => $content)); 
?>
<div id="container">
	<div id="fb-root"></div>
    <p id="miscTitlel">Contact Us</p>
    <div id="textLine"></div>
    <div class="miscDescription">Contact us anytime with any questions, concerns, or suggestions.</div>
    <form id="contactForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table id="contactTable">
    	<tbody>
        	<tr style="display:none;">
            	<th></th>
                <td id="contactNoti" class="noti"></td>
        	</tr>
            <tr>
        		<th>Name:</th>
                <td><input type="text" class="inputText" name="name" autocomplete="off" value="<?php echo (isset($current_user)) ? htmlentities($current_user->first_name.' '.$current_user->last_name) : '';?>" /></td>
        	</tr>
            <tr>
        		<th>Email:</th>
                <td><input type="text" class="inputText" name="email" value="<?php echo (isset($current_user)) ? htmlentities($current_user->email) : ''; ?>" autocomplete="off" /></td>
        	</tr>
            <tr>
        		<th style="vertical-align:top; padding-top: 10px;">Message:</th>
                <td><textarea name="msg" class="inputText descriptionText" ></textarea></td>
        	</tr>
            <tr>
            	<td colspan="2">
                	<input type="submit" class="inputSubmit" name="send" value="Send" />
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</div>
<?php get_footer(); ?>