<?php

class ContactController extends Controller{

	public function insertContact($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];
		$params = $request->getParsedBody();

		$contact_mapper = new ContactMapper($this->db);
		$result1 = $contact_mapper->insertContact($id_user1, $id_user2,$params["firebase_node"]);
		$contact_request_mapper = new ContactRequestMapper($this->db);
		$result2 = $contact_request_mapper->deleteContactRequest($id_user1,$id_user2);

		$response = $response->withJson($result1+$result2>1? true : false,201);
		return $response;
	}

	public function getContactsOfUser($request,$response,$args){
		$id_user = $args["id_user"];

		$contact_mapper = new ContactMapper($this->db);
		$result = $contact_mapper->getContactsOfUser($id_user);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function deleteContact($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];

		$contact_mapper = new ContactMapper($this->db);
		$result = $contact_mapper->deleteContact($id_user1,$id_user2);

		$response = $response->withJson($result,200);
		return $response;
	}

}