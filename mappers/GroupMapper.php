<?php

class GroupMapper extends Mapper{

	public function getGroupsOfUser($id)
	{
		$sql = "SELECT g.id,g.name FROM groups g INNER JOIN user_group ug ON g.id = ug.id_group INNER JOIN user u ON ug.id_user = u.id WHERE u.id = :id";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id"=>$id]);

		$groups = [];

		while ($row = $stm->fetch()) {
			$admin = $this->getGroupAdmin($row["id"]);
			$member_count = $this->getMemberCount($row["id"]);
			array_push($groups, new GroupEntity($row["id"], $row["name"], $admin, $member_count, null, null, null, null));
		}

		return $groups;
	}

	public function getGroup($id)
	{
		$sql = "SELECT g.id,g.name FROM groups g WHERE g.id = :id";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id"=>$id]);

		$groups = [];

		while ($row = $stm->fetch()) {
			$admin = $this->getGroupAdmin($row["id"]);
			$member_count = $this->getMemberCount($row["id"]);
			$members = $this->getGroupMembers($row["id"]);
			$next_meeting = $this->getNextMeeting($row["id"]);
			$current_meeting_count = $this->getCurrentMeetingCount($row["id"]);
			array_push($groups, new GroupEntity($row["id"], $row["name"], $admin, $member_count, $members, $next_meeting, 
				$current_meeting_count, null));
		}

		return $groups;
	}

	public function getGroupAdmin($id)
	{
		$sql = "SELECT u.id,display_name,email,photo_url FROM groups g INNER JOIN user u ON g.admin = u.id WHERE g.id = :id";

		$stm = $this->db->prepare($sql);
		$exec = $stm->execute(["id"=>$id]);

		$row = $stm->fetch();

		return new UserEntity($row);
	}

	public function getMemberCount($id)
	{
		$sql = "SELECT COUNT(*) AS count FROM user_group WHERE id_group = :id_group";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_group"=>$id]);

		$result = $stm->fetch();

		return $result["count"];
	}

	public function getGroupMembers($id)
	{
		$sql = "SELECT u.id,display_name,email,photo_url FROM user_group ug INNER JOIN user u ON ug.id_user = u.id WHERE ug.id_group = :id_group";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_group"=>$id]);

		$members = [];
		while($row = $stm->fetch()){
			array_push($members, new UserEntity($row));
		}

		return $members;
	}

	public function getNextMeeting($id)
	{
		$sql = "SELECT m.id,m.id_group,m.start_time,m.firebase_node,m.label FROM meeting m WHERE m.id_group= :id_group 
		AND m.start_time>:current_time ORDER BY m.start_time ASC LIMIT 1";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_group"=>$id,"current_time"=>time()]);

		$row = $stm->fetch();

		return new MeetingEntity($row);
	}

	public function getCurrentMeetingCount($id)
	{
		$sql = "SELECT COUNT(*) AS count FROM meeting m INNER JOIN groups g ON m.id_group = g.id WHERE g.id = :id_group 
		AND m.start_time<= :current_time";

		$stm = $this->db->prepare($sql);
		$stm->execute(["id_group" => $id,"current_time"=>time()]);

		$row = $stm->fetch();

		return $row["count"];
	}

	public function insertGroup($params)
	{
		$sql = "INSERT INTO groups (name,admin) VALUES(?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$params["name"],$params["admin"]]);

		return $result;
	}

	public function addMember($id_group,$id_user)
	{
		$sql = "INSERT INTO user_group (id_group,id_user) VALUES(?,?);";

		$stm = $this->db->prepare($sql);
		$result = $stm->execute([$id_group,$id_user]);

		return $result;
	}
}