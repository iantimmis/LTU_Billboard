<?php
	
	function getOrgs($id,$conn)
	{
		$sql = "SELECT orgId FROM user_org_join WHERE userId = {$id};";
		$result = $conn->query($sql);
		if($result->num_rows==0){$followsOrgs=false;}
		else
		{
			$followsOrgs = true;
			$followedOrgs = array();
			while($row=$result->fetch_assoc())
			{
				array_push($followedOrgs,$row['orgId']);
			}
			$infoSql = "SELECT * FROM ltuOrganization;";
			$infoResult = $conn->query($infoSql);
			$followedOrgsInfo = array();
			if($infoResult->num_rows==0){}
			else
			{
				while($infoRow=$infoResult->fetch_assoc())
				{
					if(in_array($infoRow['orgId'],$followedOrgs))
						array_push($followedOrgsInfo,$infoRow);
				}
			}
			return $followedOrgsInfo;
		}
		return "";
	}
?>