<?php

class ContactMapper extends Mapper{

	public function insertContact($id_user1,$id_user2,$firebase_node)
	{
		$sql = "INSERT INTO contact (id_user1,id_user2,firebase_node) VALUES(?,?,?);";

		$stm = $this->db->prepare($sql);
		$result1 = $stm->execute([$id_user1,$id_user2,$firebase_node]);

		$stm->closeCursor();
		$result2 = $stm->execute([$id_user2,$id_user1,$firebase_node]);

		return $result1+$result2 > 1 ? true: false;
	}

	public function getContactsOfUser($id_user)
	{
		$sql = "SELECT u.id,u.display_name,u.email,u.photo_url,c.firebase_node FROM contact c INNER JOIN user u ON c.id_user1 = u.id 
		WHERE c.id_user2 = :id_user ORDER BY u.display_name";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_user"=>$id_user]);

		$contacts = [];
		while ($row = $stm->fetch()) {
			array_push($contacts, new ContactEntity(new UserEntity($row), $row["firebase_node"]));
		}

		return $contacts;
	}

	public function deleteContact($id_user1,$id_user2)
	{
		$sql = "DELETE FROM contact WHERE (id_user1 = :id_user1 AND id_user2 = :id_user2) 
		OR (id_user1= :id_user2 AND id_user2 = :id_user1);";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_user1"=>$id_user1,"id_user2"=>$id_user2]);

		return $stm->rowCount()>1 ? true: false;
	}

}