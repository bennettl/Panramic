<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />
            <script type="text/javascript" src="js/min/mail.min.js"></script>';

get_header(array('title' => 'Join', 'content' => $content)); ?>
<div id="container">
	<div id="fb-root"></div>
    <p id="miscTitlel">Join Us</p>
    <div id="textLine"></div>
    <div class="miscDescription">Whether you are talented marketer, engineer, or just someone with tons of ideas, send us a message and we'll get back to you!</div>
    <form id="joinForm" method="post">
    <table id="contactTable">
    	<tbody>
        	<tr style="display:none;">
            	<th></th>
                <td id="joinNoti" class="noti"</td>
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
<!-- #container -->
</div>
<?php get_footer(); ?>