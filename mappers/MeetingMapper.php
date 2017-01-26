<?php

class MeetingMapper extends Mapper{

	public function getMeetings($id_group)
	{
		$sql = "SELECT m.id,m.id_group,m.start_time,m.firebase_node,m.label FROM meeting m INNER JOIN groups g ON m.id_group = g.id WHERE g.id = :id_group ORDER BY m.start_time";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_group"=>$id_group]);

		$meetings = [];
		while ($row = $stm->fetch()){
			array_push($meetings, new MeetingEntity($row));
		}

		return $meetings;
	}

	public function insertMeeting($id_group,$params)
	{
		$sql = "INSERT INTO meeting (id_group,start_time,firebase_node,label) values(?,?,?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$id_group,$params["start_time"],$params["firebase_node"],$params["label"]]);

		return $result;
	}

	public function deleteMeeting($id_meeting)
	{
		$sql = "DELETE FROM meeting WHERE id = :id_meeting";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_meeting"=>$id_meeting]);

		return $stm->rowCount()>0? true : false;
	}

}