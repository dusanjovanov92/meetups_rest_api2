<?php

class MeetingController extends Controller{

	public function getMeetings($request,$response,$args){
		$id_group = $args["id_group"];

		$meeting_mapper = new MeetingMapper($this->db);
		$result = $meeting_mapper->getMeetings($id_group);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertMeeting($request,$response,$args){
		$id_group = $args["id_group"];
		$params = $request->getParsedBody();

		$meeting_mapper = new MeetingMapper($this->db);
		$meeting = $meeting_mapper->insertMeeting($id_group,$params);

		$group_mapper = new GroupMapper($this->db);
		$group = $group_mapper->getGroup($id_group);

		$user_mapper = new UserMapper($this->db);
		$user = $user_mapper->getUserById($params["id_user"]);

		$result = $this->sendNotifications($group, $meeting,$user);

		$response = $response->withJson($result,201);
		return $response;
	}

	public function deleteMeeting($request,$response,$args){
		$id_meeting = $args["id_meeting"];

		$meeting_mapper = new MeetingMapper($this->db);
		$result = $meeting_mapper->deleteMeeting($id_meeting);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function sendNotifications($group,$meeting,$user)
	{
		$tokens = $this->getMembersTokens($group->getId());

		$url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
            	"registration_ids"=>$tokens,
            	"data"=>["group_id"=>$group->getId(),
            			"group_name"=>$group->getName(),
            			"group_admin"=>$group->getAdmin()->getId(),
            			"meeting_id"=>$meeting->getId(),
            			"meeting_label"=>$meeting->getLabel(),
            			"meeting_start_time"=>$meeting->getStartTime(),
            			"meeting_firebase_node"=>$meeting->getFireBaseNode(),
            			"user_id"=>$user->getId(),
            			"user_display_name"=>$user->getDisplayName(),
            			"user_email"=>$user->getEmail(),
            			"user_photo_url"=>$user->getPhotoUrl()]);
         
            $headers = array(
            'Content-Type: application/json',
            'Authorization:key=AAAAh1wYEZ0:APA91bE2b9IxXeO0tyqm6SAwk59eqjlis78RTMvOYXwpstf7Np83A-IyieFJ_vcfw1rqXCPzZN9NeeZHDOzccroEBLHAGaHpWSnlB6u1vzLp14qkxOmQnv3fuSWuTRMMnVFfT9p4-6BIicy98eA0zow8LBFH7jtuww');
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            
            $result = curl_exec($ch);           
            if ($result === FALSE) {
               return false;
            }
            curl_close($ch);
            return $result;
	}

	public function getMembersTokens($id_group)
	{
		$sql = "SELECT u.token FROM user u INNER JOIN user_group ug ON u.id = ug.id_user
		INNER JOIN groups g ON ug.id_group = g.id WHERE g.id = ? AND u.id <> g.admin;";

		$stm = $this->db->prepare($sql);
		$stm->execute([$id_group]);

		$tokens = [];
		while ($row = $stm->fetch()) {
			array_push($tokens,$row["token"]);
		}

		return $tokens;
	}

}