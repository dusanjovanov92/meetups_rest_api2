<?php

class GroupRequestMapper extends Mapper{

	public function insertGroupRequest($id_group,$id_user)
	{
		$sql = "INSERT INTO group_user_requests (id_group,id_user) VALUES(?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$id_group,$id_user]);

		return $result;
	}

	public function checkRequestExists($id_group,$id_user)
	{
		$sql = "SELECT EXISTS(SELECT 1 FROM group_user_requests WHERE id_group = ? AND id_user = ?) AS exist;";

		$stm = $this->db->prepare($sql);
		$stm->execute([$id_group,$id_user]);

		$result = $stm->fetch();
		
		return $result["exist"]==0? false : true;
	}

	public function getGroupRequests($id_user)
	{
		$sql = "SELECT gur.id AS id_request,g.id AS id_group,g.name,u.id,u.id AS id_user,u.display_name,u.email,u.photo_url FROM groups g INNER JOIN group_user_requests gur 
		ON gur.id_group = g.id INNER JOIN user u ON gur.id_user = u.id WHERE gur.id_user = :id_user ORDER BY gur.id DESC";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_user"=>$id_user]);

		$requests = [];
		while ($row = $stm->fetch()) {
			$user = ["id"=>$row["id_user"],"display_name"=>$row["display_name"],"email"=>$row["email"],"photo_url"=>$row["photo_url"]];
			array_push($requests, new GroupRequestEntity($row["id_request"],new GroupEntity($row["id_group"], $row["name"], new UserEntity($user))));
		}

		return $requests;
	}

	public function deleteGroupRequest($id_request)
	{
		$sql = "DELETE FROM group_user_requests WHERE id = :id_request";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_request"=>$id_request]);

		return $stm->rowCount()>0 ? true: false;
	}
}