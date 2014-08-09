<form id="signInForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<p><label>E-mail</label>
	<input class="inputText signInInput" type="text" name="siEmail"
	<?php
	if (isset($_COOKIE['e'])){
		$email = htmlentities($_COOKIE['e']);
		echo ' value="'.$email.'" ';
	} ?>
	autocomplete="off" /></p>
	<p style="margin-bottom: 5px"><label for="siPassword">Password</label>          
	
	<input class="inputText signInInput" type="password" name="siPassword"/></p>
	<a href="resetpassword" style="font-size: 10px;">Forgot Password?</a>
					
	<p style="margin: 7px 0px 0px 0px;"><input type="checkbox" name="rememberMe" style="margin-left:0px" checked="checked" value="yes" /> <span>Remember Me</span>
	<input class="inputSubmit" id="signInSubmit" type="submit" name="signIn" value="Log In"/>
	</p>
	
	<p id="loginFb"> Or login with Facebook </p>
	<p><div id="signInFb"></div></p>
	<p id="signInError"></p>
</form>	