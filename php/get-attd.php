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
		if($test = $html->find('div[class=paneltitle01]'))
		{		
		
			
			if($data = $cc->get('evarsity.srmuniv.ac.in/srmswi/resource/StudentDetailsResources.jsp?resourceid=7'))
			{
				$html = str_get_html($data);
				$table = $html->find('table');
				$tr = $table[0]->find('tr'); 
				if($data = $cc->get('evarsity.srmuniv.ac.in/srmswi/resource/StudentDetailsResources.jsp?resourceid=5'))
				{				
					$html = str_get_html($data);
					$table = $html->find('table');					
					$otr = $table[1]->find('tr');								
					$nos = count($otr);
					echo " { \n";
					echo " \"subjects\" : [";
					for($i=2;$i<$nos;$i++)
					{
						$td = $otr[$i]->find('td');					
						if($i==$nos-1)
								echo " \"".rtrim(ltrim($td[0]->plaintext,' '),' ')."\"],\n";
							else
								echo " \"".rtrim(ltrim($td[0]->plaintext,' '),' ')."\",";
					}
					$nos = count($tr)-4;
					for($i=0;$i<$nos;$i++)	
						{		
							$td = $tr[$i+3]->find('td');							
								echo " \"".rtrim(ltrim($td[0]->plaintext," ")," ")."\" : ";
								echo " { \n";			
								echo " \"sub-desc\" : \"".rtrim(ltrim($td[1]->plaintext," ")," ")."\",\n";
								echo " \"max-hrs\" : ".$td[2]->plaintext.",\n";
								echo " \"attd-hrs\" : ".$td[3]->plaintext.",\n";
								echo " \"abs-hrs\" : ".$td[4]->plaintext.",\n";
								echo " \"avg-attd\" : ".$td[7]->plaintext."\n";   
						    	echo " } ,\n";
						}
							$td = $tr[$nos+3]->find('td');		    
						    	echo " \"total\" : ";
								echo " { \n";
								echo " \"tot-hrs\" : ".$td[1]->plaintext.",\n";
								echo " \"tot-attd-hrs\" : ".$td[2]->plaintext.",\n";
								echo " \"tot-abs-hrs\" : ".$td[3]->plaintext.",\n";
								echo " \"tot-avg-attd\" : ".$td[6]->plaintext."\n";			
						    	echo " } ,\n";
						    	echo "\"error\":false \n";
					echo " } ";
				}
				else
				{
					echo " {\n  \"error\":\"Unable to connect to Time Table Detais\", \n";
					echo "  \"code\":\"302\" \n }";		
				}
			}
			else
			{
				echo " {\n  \"error\":\"Unable to connect to Attd Detais\", \n";
				echo "  \"code\":\"302\" \n }";
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