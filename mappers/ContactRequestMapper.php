<?php

class ContactRequestMapper extends Mapper{

	public function insertContactRequest($id_user1,$id_user2)
	{
		$sql = "INSERT INTO contact_requests (sender,receiver) VALUES(?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$id_user1,$id_user2]);

		return $result;
	}

	public function deleteContactRequest($id_user1,$id_user2)
	{
		$sql = "DELETE FROM contact_requests WHERE (sender = :sender1 AND receiver = :receiver2) OR (sender = :sender2 AND receiver =:receiver1);";

		$stm = $this->db->prepare($sql);
		$stm->execute(["sender1"=>$id_user1,"sender2"=>$id_user2,"receiver1"=>$id_user1,"receiver2"=>$id_user2]);

		return $stm->rowCount()>0? true: false;
	}

	public function getContactRequests($id_user)
	{
		$sql = "SELECT cr.id_request,u.id,u.display_name,u.email,u.photo_url FROM contact_requests cr INNER JOIN user u 
		ON cr.sender = u.id WHERE cr.receiver= :id_user ORDER BY cr.id_request DESC";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_user"=>$id_user]);

		$requests= [];
		while ($row = $stm->fetch()) {
			array_push($requests, new ContactRequestEntity($row["id_request"],new UserEntity($row)));
		}

		return $requests;
	}

}