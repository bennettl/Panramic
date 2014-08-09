<?php
require_once('connect.php');
redirect_not_staff();
?>
<div id="masse" class="pageLayout">
<div class="pageHd">Mass Email</div>
<form id="masseForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div id="masseNoti" class="noti pageNoti"></div>
	<table id="masseTable" class="tableForm">
		<tr>
			<th class="label">Subject:</th>
			<td><input class="inputText" type="text" name="subject" autocomplete="off" /></td>
		</tr>
		<tr>
			<th class="label">Message:</th>
			<td><textarea name="message" class="inputText descriptionText"></textarea></td>
		</tr>
		<tr>
			<th class="label">Audience:</th>
			<td>
			<select name="audience">
				<option value="u">Users</option>
				<option value="a">Admins</option>
				<option value="ao">Admins and Officers</option>
			</select>
			</td>
		</tr>
		<tr>
			<th class="label">Password:</th>
			<td><input class="inputText" type="password" name="password"  autocomplete="off" /></td>
		</tr>
		<tr>
			<th></th>
			<td>
			<input id="masseReset" type="reset" style="visibility:hidden" />
			<input class="inputSubmit feedbackSubmit" type="submit" name="sendFeedback" value="Send" />
			</td>
		</tr>
	</table>
</form>
<!-- #masse -->
</div>