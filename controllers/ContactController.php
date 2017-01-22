<?php

class ContactController extends Controller{

	public function insertContact($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];

		$contact_mapper = new ContactMapper($this->db);
		$result = $contact_mapper->insertContact($id_user1, $id_user2);

		$response = $response->withJson($result,201);
		return $response;
	}

	
}