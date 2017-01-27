<?php

class TestController extends Controller{

	public function deleteAll($request,$response,$args){
		$sql= "DELETE FROM user; DELETE FROM groups; DELETE FROM user_group; DELETE FROM contact;
		DELETE FROM contact_requests; DELETE FROM group_user_requests; DELETE FROM meeting;
		DELETE FROM meeting_response;";

		$stm = $this->db->query($sql);
		$stm->execute();

		$response = $response->withJson($this->db->errorCode(),200);
		return $response;
	}

	public function deleteGroups($request,$response,$args){
		$db = $this->get("db");
		$sql = "DELETE FROM groups; DELETE FROM user_group;";

		$stm = $db->query($sql);
		$stm->execute();

		$response = $response->withJson($result,200);
		return $response;
	}
	
	public function deleteRequests($request,$response,$args){
		$db = $this->get("db");
		$sql = "DELETE FROM contact_requests;";

		$stm = $db->query($sql);
		$stm->execute();

		$response = $response->withJson($result,200);
		return $response;
	}	

	public function insertRequests($request,$response,$args){
		$db = $this->get("db");
		$sql_select_users = "SELECT id FROM user WHERE id<>28;";
		$stm = $db->query($sql_select_users);
		$stm->execute();

		$users_ids = [];
		while($row = $stm->fetch()){
		array_push($users_ids,$row["id"]);
		}
		$stm->closeCursor();

		$sql_insert_cr = "INSERT INTO contact_requests (sender,receiver) VALUES(?,28);";

		$stm2 = $db->prepare($sql_insert_cr);

		foreach ($users_ids as $value) {
			$stm2->execute([$value]);
		}

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertGroups($request,$response,$args){
		$db = $this->get("db");
		$sql_select_users = "SELECT id FROM user;";
		$stm = $db->query($sql_select_users);
		$stm->execute();

		$users_ids = [];
		while($row = $stm->fetch()){
			array_push($users_ids,$row["id"]);
		}
		$stm->closeCursor();

		$sql_insert_groups = "INSERT INTO groups (name,admin) VALUES(?,28);";

		$stm2 = $db->prepare($sql_insert_groups);

		$group_ids = [];

		for ($i=1; $i < 6; $i++) { 
			$stm2->execute(["Grupa".$i]);
			$stm2->closeCursor();
			array_push($group_ids,$db->lastInsertId());
		}

		$sql_insert_members = "INSERT INTO user_group (id_user,id_group) VALUES(?,?);";

		$stm3 = $db->prepare($sql_insert_members);

		foreach ($group_ids as $value) {
			foreach ($users_ids as $value2) {
				$stm3->execute([$value2,$value]);
				$stm3->closeCursor();
			}
		}

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertContacts($request,$response,$args){
		$db = $this->get("db");
		$sql_select_users = "SELECT id FROM user WHERE id<>28;";
		$stm = $db->query($sql_select_users);
		$stm->execute();

		$users_ids = [];
		while($row = $stm->fetch()){
			array_push($users_ids,$row["id"]);
		}
		$stm->closeCursor();

		$sql_insert = "INSERT INTO contact (id_user1,id_user2,firebase_node) VALUES(?,?,?);
		INSERT INTO contact (id_user1,id_user2,firebase_node) VALUES(?,?,?);";

		$stm2 = $db->prepare($sql_insert);
	
		foreach ($users_ids as $key => $value) {
			$stm2->execute([28,$value,"-".($key+1),$value,28,"-".($key+1)]);
			$stm2->closeCursor();
		}

		$response = $response->withJson($result,200);
		return $response;
	}
}