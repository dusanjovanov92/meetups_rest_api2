<?php

class MeetingResponseController extends Controller{

	public function getMeetingResponses($request,$response,$args){
		$id_meeting = $args["id_meeting"];

		$meeting_response_mapper = new MeetingResponseMapper($this->db);
		$result = $meeting_response_mapper->getMeetingResponses($id_meeting);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertMeetingResponse($request,$response,$args){
		$id_meeting = $args["id_meeting"];
		$id_user = $args["id_user"];
		$params = $request->getParsedBody();

		$meeting_response_mapper = new MeetingResponseMapper($this->db);
		$result = $meeting_response_mapper->insertMeetingResponse($id_meeting,$id_user,$params);

		$response = $response->withJson($result,201);
		return $response;
	}

	public function updateMeetingResponse($request,$response,$args){
		$id_response = $args["id_response"];
		$params = $request->getParsedBody();

		$meeting_response_mapper = new MeetingResponseMapper($this->db);
		$result = $meeting_response_mapper->updateMeetingResponse($id_response,$params);

		$response = $response->withJson($result,200);
		return $response;
	}
}