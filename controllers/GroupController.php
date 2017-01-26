<?php

class GroupController extends Controller	
{
	public function getGroupsOfUser($request,$response,$args){
		$id = $args["id"];

		$group_mapper = new GroupMapper($this->db);
		$result = $group_mapper->getGroupsOfUser($id);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function getGroup($request,$response,$args){
		$id = $args["id"];

		$group_mapper = new GroupMapper($this->db);
		$result = $group_mapper->getGroup($id);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function insertGroup($request,$response,$args){
		$params = $request->getParsedBody();

		$group_mapper = new GroupMapper($this->db);
		$result = $group_mapper->insertGroup($params);

		$response = $response->withJson($result,201);
		return $response;
	}

	public function addMember($request,$response,$args){
		$id_group = $args["id_group"];
		$id_user = $args["id_user"];

		$group_mapper = new GroupMapper($this->db);
		$result = $group_mapper->addMember($id_group, $id_user);

		$response = $response->withJson($result,201);
		return $response;
	}

	public function deleteGroup($request,$response,$args){
		$id_group = $args["id_group"];

		$group_mapper = new GroupMapper($this->db);
		$result = $group_mapper->deleteGroup($id_group);

		$response = $response->withJson($result,200);
		return $response;
	}

}