<?php

class UserMapper extends Mapper{

	public function getUserById($id)
	{
		$sql = "SELECT id,display_name,email,photo_url FROM user WHERE id = :id";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id" => $id]);
		$row = $stm->fetch();

		return new UserEntity($row);
	}

	public function getUserByEmail($email)
	{
		$sql = "SELECT id,display_name,email,photo_url FROM user WHERE email = :email";

		$stm = $this->db->prepare($sql);
		$stm->execute(["email" => $email]);
		$row = $stm->fetch();

		return new UserEntity($row);
	}

	public function getUsersByDisplayName($query)
	{
		$sql = "SELECT id,display_name,email,photo_url FROM user WHERE display_name like :query ORDER BY display_name";

		$stm = $this->db->prepare($sql);
		$stm->execute(["query" => $query."%"]);

		$results = [];
		while($row = $stm->fetch()){
			array_push($results,new UserEntity($row));
		}

		return $results;
	}

	public function insertUser($params)
	{
		$sql = "INSERT INTO user (display_name,email,photo_url,token) VALUES(?,?,?,?);";

		$stm = $this->db->prepare($sql);
		$stm->execute([$params["display_name"],$params["email"],$params["photo_url"],$params["token"]]);

		return $this->db->lastInsertId();
	}

	public function emailExists($email)
	{
		$sql = "SELECT EXISTS(SELECT 1 FROM user WHERE email = :email) exist";

		$stm = $this->db->prepare($sql);
		$stm->execute(["email"=>$email]);

		$result = $stm->fetch(PDO::FETCH_ASSOC);

		return $result["exist"]==1 ? true : false;
	}

	public function updateUser($id,$params)
	{
		$sql = "UPDATE user SET token = :token WHERE id = :id";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute(["token"=>$params["token"],"id" => $id]);

		return $result;
	}

	public function getRelationship($id_user1,$id_user2)
	{
		$sql = "SELECT EXISTS(SELECT 1 FROM contact_requests cr WHERE cr.sender = :sender AND cr.receiver = :receiver) AS exist;";

		$stm = $this->db->prepare($sql);
		$stm->execute(["sender"=>$id_user1,"receiver"=>$id_user2]);
		$result1 = $stm->fetch();
		if($result1["exist"]==1){
			return 1;
		}

		$stm->closeCursor();
		$stm->execute(["sender"=>$id_user2,"receiver"=>$id_user1]);
		$result2 = $stm->fetch();

		if($result2["exist"]==1){
			return 2;
		}

		$sql = "SELECT EXISTS(SELECT 1 FROM contact c WHERE c.id_user1 = ? AND c.id_user2 = ?) AS exist;";

		$stm = $this->db->prepare($sql);
		$stm->execute([$id_user1,$id_user2]);
		$result3 = $stm->fetch();
		if($result3["exist"]==1){
			return 3;
		}
		
		return 0;
	}

	public function updateToken($params,$email)
	{
		$sql = "UPDATE user SET token = ? WHERE email = ?;";

		$stm = $this->db->prepare($sql);
		$stm->execute([$params["token"],$email]);

		$user = $this->getUserByEmail($email);

		return $user;
	}
}

