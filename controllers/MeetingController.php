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
		$result = $meeting_mapper->insertMeeting($id_group,$params);

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

}