<?php

$file = 'cookies.txt';
file_put_contents($file,'');

if(isset($_POST['regno']) && isset($_POST['pass']))
{
	require_once('curl.php');
	require_once('simple_html_dom.php');	

	$cc = new cURL(); 

	if($data = $cc->get('evarsity.srmuniv.ac.in/srmswi/usermanager/youLogin.jsp?txtSN='.$_POST["regno"].'&txtPD='.$_POST["pass"].'&txtPA=1'))
	{	
	$html = str_get_html($data);

		if($html->find('div[class=paneltitle01]'))
		{		
			
			if($data = $cc->get('evarsity.srmuniv.ac.in/srmswi/resource/StudentDetailsResources.jsp?resourceid=1'))
			{
				$html = str_get_html($data);	
				$table = $html->find('table');
				$tr = $table[1]->find('tr'); 		

				if($data = $cc->get('evarsity.srmuniv.ac.in/srmswi/feepayment/StudentFeePayment.jsp'))
				{	
				 	$html = str_get_html($data);	
					$feetable = $html->find('table');					
					$feetr = $feetable[0]->find('tr'); 										
					$total = count($tr);					
					
					echo " { \n";

						$td = $tr[1]->find('td');
						echo " \"name\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $tr[2]->find('td');
						echo " \"regno\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $tr[4]->find('td');
						echo " \"course\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $feetr[2]->find('td');
						$input = $td[1]->find('input');
						echo " \"studentid\" : \"".$input[0]->value."\",\n";

						$td = $feetr[4]->find('td');
						$input = $td[1]->find('input');
						
						if($input[0]->value=="I")
							$sem = 1;
						else if($input[0]->value=="II")
							$sem = 2;
						else if($input[0]->value=="III")
							$sem = 3;
						else if($input[0]->value=="IV")
							$sem = 4;
						else if($input[0]->value=="V")
							$sem = 5;
						else if($input[0]->value=="VI")
							$sem = 6;
						else if($input[0]->value=="VII")
							$sem = 7;
						else if($input[0]->value=="VIII")
							$sem = 8;
						else if($input[0]->value=="IX")
							$sem = 9;
						else if($input[0]->value=="X")
							$sem = 10;
						else if($input[0]->value=="XI")
							$sem = 11;
						else if($input[0]->value=="XII")
							$sem = 12;												

						$year = ceil($sem/2);
						echo " \"semester\" : ".$sem.",\n";
						echo " \"year\" : ".$year.",\n";

						$td = $tr[$total-4]->find('td');
						echo " \"email\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";			

						$td = $tr[$total-8]->find('td');
						echo " \"dob\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $tr[$total-7]->find('td');
						echo " \"sex\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $tr[$total-5]->find('td');
						echo " \"address\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						$td = $tr[$total-3]->find('td');
						echo " \"pincode\" : \"".rtrim(ltrim($td[1]->plaintext,' '),' ')."\",\n";

						echo " \"error\":false \n";

					echo " } ";

						$td = $tr[1]->find('td');
						$img = $td[2]->find('img');	
						$src = $img[0]->src;		
						$td = $tr[2]->find('td');				
						if($data = $cc->get('http://evarsity.srmuniv.ac.in/srmswi/resource/'.$src))
						{
							$file = rtrim(ltrim($td[1]->plaintext,' '),' ').'.jpg';
							$fp = fopen('studentImages/'.$file,'w');
							fwrite($fp, $data); 
							fclose($fp);				
						}
						else
						{
							echo " {\n  \"error\":\"Unable to Download File from ERP\", \n";
							echo "  \"code\":\"707\" \n }";
						}	
				}
				else
				{
					echo " {\n  \"error\":\"Unable to connect to Additional Details\", \n";
					echo "  \"code\":\"301\" \n }";
				}

			}			
			else
			{
				echo " {\n  \"error\":\"Unable to connect to Student Detais\", \n";
				echo "  \"code\":\"300\" \n }";
			}	
		}
		else
		{
			echo " {\n  \"error\":\"Wrong Regno Password\", \n";
			echo "  \"code\":\"100\" \n }";
		}

	}	
	else
	{
		echo " {\n  \"error\":\"Unable to connect to SRM HOME\", \n";
		echo "  \"code\":\"200\" \n }";
	}	

}
else
{
	echo " {\n  \"error\":\"Something Went Wrong\", \n";
	echo "  \"code\":\"500\" \n }";
}	




?> 