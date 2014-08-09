<div id="loginMod" class="mod">
	<div class="modHd">Panramic</div>
	<div class="delete"></div>
	<p>You must <a href="<?php echo MAIN_URL; ?>"><u>sign up</u></a> or be <a href="<?php echo MAIN_URL; ?>"><u>logged into</u></a> Panramic to interact with an events feed</p>
</div>
<div id="reportMod" class="mod">
    <div class="modHd">Report</div>
    <span class="delete"></span>
    <p class="noti" style="padding-left: 10px;"></p>
    <form id="reportForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table>
		<tr>
			<td><textarea name="comment" class="inputText descriptionText inactiveText">Please tell us what the problem is</textarea></td></tr>
		<tr>
			<td>
				<input type="hidden" name="eventId" />
				<input type="hidden" name="msgId" />
				<input class="inputSubmit feedbackSubmit" type="submit" name="sendReport" value="Report" />
			</td>
		</tr>
    </table>
    </form>
</div>
<div id="feedbackMod" class="mod">
    <div class="modHd">What do you think?</div>
    <span class="delete"></span>
    <p id="feedbackNoti" class="noti" style="padding-left: 5px;">Thank you so much for your feedback!</p>
    <form id="feedBackForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table>
		<tr><td><textarea name="msg" class="inputText feedbackText inactiveText">Ideas, suggestions, comments, feedback? We love to hear from you!</textarea></td></tr>
		<tr><td><input class="inputSubmit feedbackSubmit" type="submit" name="sendFeedback" value="Send" /></td></tr>
    </table>
    </form>
</div>
<div id="footer">
	<div id="copyright">Panramic &#169;   
		<?php echo date('Y',time()); ?>
	</div>
    <ul id="footlinks">
    <?php
	// If this is a facebook app, dont show the footlinks
	if (!$facebook): ?>
        <li><a href="about">About</a></li>
        <li><a href="contact">Contact</a></li>
        <li><a href="join">Join Us</a> </li> 
        <li><a href="privacy">Privacy</a></li>
        <li><a href="terms">Terms</a> </li>
	<?php endif; ?>	
    </ul>
<!-- #footer -->
</div>
<!-- container -->
</div>
<?php if ($content){ echo $content; } 	// If there is some content, then display them ?>
</body>
</html>
<?php if ($dbc){ mysqli_close($dbc);  }  ?>