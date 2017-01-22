<?php

class ContactMapper extends Mapper{

	public function insertContact($id_user1,$id_user2)
	{
		$sql = "INSERT INTO contact (id_user1,id_user2) VALUES(?,?);";

		$stm = $this->db->prepare($sql);
		$result1 = $stm->execute([$id_user1,$id_user2]);

		$stm->closeCursor();
		$result2 = $stm->execute([$id_user2,$id_user1]);

		return $result1+$result2 > 1 ? true: false;
	}
}