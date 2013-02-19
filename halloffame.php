<html>
	<head>
		<title>
			HALL OF FAME
		</title>
	</head>
	
	<body>
		
		<h1 align='center'>
			Hall of Fame
		</h1>
		<table border="1" bordercolor="#000000" style="background-color:#FFFFFF" width="70%" cellpadding="0" cellspacing="0" align="center">
		
			<tr align='center'>			
				<td><u>Address</u></td>
				<td><u>Units<u></td>
				<td><u>Link<u></td>
			</tr>
			
			<?php
				require_once ("dbfunctions.php");
				openDB();
				$res=getAllPayed();
				closeDB();
				foreach ($res as $row)
				{
					echo"
						<tr align='center'>			
							<td>$row[0]</td>
							<td> "
								.sprintf("%01.2f", $row[1]/100).
							"</td>							
							<td>
								<a href='http://blockexplorer.com/address/$row[0]'>
									Link
								</a>
							</td>							
						</tr>
					";
				}
								
				
			?>
		
		</table>




		
	</body>
	
</html>