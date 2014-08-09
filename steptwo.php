<?php 
require_once('template.php');
require_once(CLASS_DIR.'network.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

// If nextBtn isset, then establish the connections (if there are any) and redirect user
if (isset($_POST['nextBtn'])){
	$network_ids = array_filter($_POST['network']); // remove empty elements
	$current_user->removeNetworks();
	$current_user->postNetworks($network_ids);

	// Redirect user to step three regardless of whether or not they choosen a network
	mysqli_close($dbc);
	$stepThree = '/stepthree';
	header('Location:'.$stepThree);
	exit;
}

$content = '<link rel="stylesheet" type="text/css" href="css/steptwo.css" />
			<script type="text/javascript" src="js/steptwo.js"></script>'; 
get_header(array('noHeader' => true, 'content' => $content));
?>
<div id="networkBg"></div>
<div id="container">
	<div id="topContainer">
     	<p id="networkText"> Network</p>
      	 <div id="description">
       		<p> Base on your <strong>city</strong>, you will know about city-wide events such as concerts, conferences, festivals, etc.</p>
      		<p> Base on your <strong>university</strong>, you will know about campus relevant events such as comedy shows, performances, concerts, parties, etc. </p>
   		 </div>
    <!-- #topContainer -->    
	</div>
	<div id="bottomContainer">
        <div id="networkContainer">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table class="networkTable">
                 <tr>
						<td>
							<select name="network[]" style="float:left;">
								<option value="">City</option>
								<?php
								// Loop through all city networks and display them
								foreach (Network::get_all_networks('city') as $network => $networkId){
									echo '<option value="'.$networkId.'">'.$network.'</option>';
								}
								?>
							</select>
					   </td>
					</tr>
				 <tr>
						<td>
							<select name="network[]">
								<option value="">University</option>
								<?php
								// Loop through all university networks and display them
								foreach (Network::get_all_networks('university') as $network => $networkId){
									echo '<option value="'.$networkId.'">'.$network.'</option>';
								}
								?>
							</select>
					   </td>
				</tr>
				<tr>
					<td><input type="submit" name="nextBtn" id="nextBtn" value="Next" /></td>
				</tr>
            </table>
               <img id="networkImg" src="css/images/default.png" style="border-bottom: 1px solid #D2D2D2; border-right:1px solid #D2D2D2" />
			</form>
			<?php
			// Loop through and load network images
			for ($i=1; $i < 3;$i++){
				echo'<img src="css/images/networks/'.$i.'.png" style="display:none;"/>';
			}
			?>	
        <!-- #networkContainer -->   
        </div>
    <!-- #bottomContainer -->   
	</div>
<!-- #container -->
</div>
</body>
</html>