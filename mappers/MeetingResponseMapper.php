<?php

class MeetingResponseMapper extends Mapper{

	public function getMeetingResponses($id_meeting)
	{
		$sql = "SELECT mr.id AS id_response,id_meeting,response,u.id AS id_user,u.display_name,u.email,u.photo_url FROM meeting_response mr INNER JOIN user u ON mr.id_user = u.id WHERE id_meeting = :id_meeting";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_meeting"=>$id_meeting]);

		$responses = [];
		while ($row = $stm->fetch()) {
			$user = ["id"=>$row["id_user"],"display_name"=>$row["display_name"],"email"=>$row["email"],"photo_url"=>$row["photo_url"]];
			array_push($responses, new MeetingResponseEntity($row["id_response"], $row["id_meeting"], new UserEntity($user), 
				$row["response"]));
		}

		return $responses;
	}

	public function insertMeetingResponse($id_meeting,$id_user,$params)
	{
		$sql = "INSERT INTO meeting_response (id_meeting,id_user,response) VALUES (?,?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$id_meeting,$id_user,$params["response"]]);

		return $result;
	}

	public function updateMeetingResponse($id_response,$params)
	{
		$sql = "UPDATE meeting_response SET response = :response WHERE id= :id_response;";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute(["response"=>$params["response"],"id_response"=>$id_response]);

		return $result;
	}
}