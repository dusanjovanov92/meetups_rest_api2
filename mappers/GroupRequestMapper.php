<?php

class GroupRequestMapper extends Mapper{

	public function getGroupRequests($id_user)
	{
		$sql = "SELECT g.id AS id_group,g.name,u.id,u.id AS id_user,u.display_name,u.email,u.photo_url FROM groups g INNER JOIN group_user_requests gur 
		ON gur.id_group = g.id INNER JOIN user u ON gur.id_user = u.id WHERE gur.id_user = :id_user";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_user"=>$id_user]);

		$requests = [];
		while ($row = $stm->fetch()) {
			$user = ["id"=>$row["id_user"],"display_name"=>$row["display_name"],"email"=>$row["email"],"photo_url"=>$row["photo_url"]];
			array_push($requests, new GroupRequestEntity(new GroupEntity($row["id_group"], $row["name"], new UserEntity($user))));
		}

		return $requests;
	}
}