<form id="signUpForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div id="signUpFb"></div>
		<div id="errorMsg2" style="margin-bottom: 10px;"></div>
    <table id="signUpTable">
			<tr id="errorContainer" style="display:none;">
				<td colspan="2" style="padding:0"><div id="errorMsg"></div></td>
			</tr>
           <tr>
               <td class="label"><label for="fullname">Full name:</label></td>
               <td class="input"><input class="inputText signUpText" type="text" name="fullname" autocomplete="off" /></td>
           </tr>
           <tr>
               <td class="label"><label for="email">Email:</label></td>
               <td class="input"><input class="inputText signUpText" type="text" name="email" autocomplete="off" /></td>
           </tr>
           <tr>
               <td class="label"><label for="password">Password:</label></td>
               <td class="input"><input class="inputText signUpText" type="password" name="password" autocomplete="off" /></td>
           </tr>
           <tr>
               <td class="label"> Birthday: </td>
               <td class="input">
               <select name="month">
                    <option value="-1">Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="date" >
                    <option value="-1">Date</option>
                    <?php
					// Increment the options for date and sticky it. f it is less than 10, then we concatenate a 0 on the first digit
					for ($date = 1; $date <= 31; $date++){
						if ($date < 10){
							$date = "0" + '\''. $date . '' ; 
						}
						if (isset($_POST['date']) && intval($_POST['date']) == $date) {
							echo '<option selected ="selected" value="'.$date.'">'.$date.'</option>';
						} else{
							echo '<option value="'.$date.'">'.$date.'</option>';
						}
						$date = intval($date);
					}
					?>
                </select>
                <select name="year">
                    <option value="-1">Year</option>
                    <?php                   
					// Decrement the values for year and sticky it
					for ($year= 2011; $year >= 1950; $year--){
					   if (isset($_POST['year']) && intval($_POST['year']) == $year) {
						  	echo '<option selected ="selected" value="'.$year.'">'.$year.'</option>';
						} else{
						  	echo '<option value="'.$year.'">'.$year.'</option>';
						}                       
					}
					?>
                </select> 
           </td>
         </tr>
         <tr>
         	<td class="label">Sex:</td>
            <td class="input">
            	<select name="gender">
                    <option value="-1">Sex</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </td>
         </tr>
         <tr>
         	<td></td>
            <td><input id="signUpBtn" class="inputSubmit" type="submit" name="signUp" value="Sign Up!" /></td>
         </tr>		 
       	</table>
       </form>