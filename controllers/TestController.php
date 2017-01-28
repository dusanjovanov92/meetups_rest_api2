<?php

class TestController extends Controller{

	public function insertUsers($request,$response,$args){
		$sql = "INSERT INTO user (display_name,email,photo_url,token) VALUES(?,?,?,?);";

		$stm = $this->db->prepare($sql);

		$imena = ["Pera","Zika","Mika","Joca"];
		$prezimena = ["Peric","Zikic","Mikic","Jocic"];

		foreach ($imena as $value) {
			foreach ($prezimena as $value2) {
				$name = $value." ";
				$email = strtolower($value)."@";
				$name.=$value2;
				$email.=strtolower($value2).".com";
				$stm->execute([$name,$email,null,null]);
			}
			
		}

		$stm->execute(["Dusan Jovanov","jovanovdusan1@gmail.com","https://lh5.googleusercontent.com/-7wJjSNj1VdQ/AAAAAAAAAAI/AAAAAAAAAG0/EbDsGeLHofg/s96-c/photo.jpg",null]);

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}

	public function deleteUsers($request,$response,$args){
		$result = $this->deleteTableValues("user");

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertGroups($request,$response,$args){
		$users_ids = $this->selectUsersIds(true);

		$sql_insert_groups = "INSERT INTO groups (name,admin) SELECT ? as name,id FROM user
		WHERE email = 'jovanovdusan1@gmail.com';";

		$stm = $this->db->prepare($sql_insert_groups);

		$group_ids = [];

		for ($i=1; $i <= 10; $i++) { 
			$stm->execute(["Grupa".$i]);
			$stm->closeCursor();
			array_push($group_ids,$this->db->lastInsertId());
		}

		$sql_insert_members = "INSERT INTO user_group (id_user,id_group) VALUES(?,?);";

		$stm2 = $this->db->prepare($sql_insert_members);

		foreach ($group_ids as $value) {
			foreach ($users_ids as $value2) {
				$stm2->execute([$value2,$value]);
				$stm->closeCursor();
			}
		}

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}

	public function deleteGroups($request,$response,$args){
		$result = $this->deleteTableValues("groups");
		$result2 = $this->deleteTableValues("user_group");

		$response = $response->withJson($result and $result2,200); 
		return $response;
	}

	public function insertMeetings($request,$response,$args){
		$sql = "INSERT INTO meeting (id_group,start_time,firebase_node,label) VALUES(?,?,?,?);";

		$stm3 = $this->db->prepare($sql);

		$timestamps= [1485620357,1484006400,1488754800,1466703000,1485877500];

		foreach ($group_ids as $value) {
			for ($i=1; $i <= 5; $i++) { 
				$stm3->execute([$value,$timestamps[$i-1],null,"Sastanak ".$i]);
				$stm3->closeCursor();
			}
		}

		$sql = "INSERT INTO meeting;";

		$stm3 = $this->db->prepare($sql);



		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertContacts($request,$response,$args){
		$users_ids = $this->selectUsersIds(false);

		$sql_insert = "INSERT INTO contact (id_user1,id_user2,firebase_node) 
		SELECT ? as id_user1,id,?as firebase_node
		FROM user
		WHERE email = 'jovanovdusan1@gmail.com';
		INSERT INTO contact (id_user1,id_user2,firebase_node)
		SELECT id,?as id_user2,?as firebase_node
		FROM user
		WHERE email = 'jovanovdusan1@gmail.com';";

		$stm = $this->db->prepare($sql_insert);
	
		foreach ($users_ids as $key => $value) {
			$stm->execute([$value,"-".($key+1),$value,"-".($key+1)]);
			$stm->closeCursor();
		}

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}

	public function deleteContacts($request,$response,$args){
		$result = $this->deleteTableValues("contact");

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertRequests($request,$response,$args){
		$users_ids = $this->selectUsersIds(false);

		$sql_insert_cr = "INSERT INTO contact_requests (sender,receiver) SELECT ? AS sender,id FROM user 
		WHERE email= 'jovanovdusan1@gmail.com';";

		$stm2 = $this->db->prepare($sql_insert_cr);

		foreach ($users_ids as $value) {
			$stm2->execute([$value]);
		}

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}

	public function deleteRequests($request,$response,$args){
		$result = $this->deleteTableValues("contact_requests");

		$response = $response->withJson($result,200);
		return $response;
	}	



	public function selectUsersIds($all)
	{
		$sql = null;

		if($all){
			$sql = "SELECT id FROM user;";
		}
		else{
			$sql = "SELECT id FROM user WHERE id<>(SELECT id FROM user WHERE email = 'jovanovdusan1@gmail.com');";
		}

		$stm = $this->db->query($sql);
		$stm->execute();

		$users_ids = [];
		while($row = $stm->fetch()){
			array_push($users_ids,$row["id"]);
		}

		return $users_ids;
	}

	public function deleteTableValues($table_name)
	{
		$sql = "DELETE FROM $table_name;";

		$stm = $this->db->query($sql);
		$result = $stm->execute();

		return $result;
	}

	public function deleteAll($request,$response,$args){
		$sql= "DELETE FROM user; DELETE FROM groups; DELETE FROM user_group; DELETE FROM contact;
		DELETE FROM contact_requests; DELETE FROM group_user_requests; DELETE FROM meeting;
		DELETE FROM meeting_response;";

		$stm = $this->db->query($sql);
		$stm->execute();

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}
}