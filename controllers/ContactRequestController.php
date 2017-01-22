<?php

class ContactRequestController extends Controller{

	public function insertContactRequest($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];

		$contact_request_mapper= new ContactRequestMapper($this->db);
		$result = $contact_request_mapper->insertContactRequest($id_user1, $id_user2);

		$response = $response->withJson($result,201);
		return $response;
	}

	public function deleteContactRequest($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];

		$contact_request_mapper= new ContactRequestMapper($this->db);
		$result = $contact_request_mapper->deleteContactRequest($id_user1, $id_user2);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function getRequests($request,$response,$args){
		$id_user = $args["id_user"];

		$contact_request_mapper= new ContactRequestMapper($this->db);
		$contactRequests = $contact_request_mapper->getContactRequests($id_user);
		$group_request_mapper =new GroupRequestMapper($this->db);
		$groupRequests = $group_request_mapper->getGroupRequests($id_user);

		$requests = ["contactRequests"=> $contactRequests,"groupRequests"=>$groupRequests];

		$response = $response->withJson($requests,200);
		return $response;
	}
}