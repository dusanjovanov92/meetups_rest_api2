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
		$sql = "SELECT id,display_name,email,photo_url FROM user WHERE display_name like :query";

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
		$result = $stm->execute([$params["display_name"],$params["email"],$params["photo_url"],$params["token"]]);

		return $result;
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
}

