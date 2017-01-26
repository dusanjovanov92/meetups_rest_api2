<?php

class GroupRequestController extends Controller{

	public function insertGroupRequest($request,$response,$args){
		$id_group = $args["id_group"];
		$id_user = $args["id_user"];

		$group_mapper = new GroupMapper($this->db);
		$member_exists = $group_mapper->checkMemberExists($id_group,$id_user);

		$result = null;

		$group_request_mapper = new GroupRequestMapper($this->db);

		if($member_exists){
			$result = 3;
		}
		else{
			$request_exists = $group_request_mapper->checkRequestExists($id_group, $id_user);
			if($request_exists){
				$result = 2;
			}
			else{
				$group_request_mapper->insertGroupRequest($id_group, $id_user);
				$result = 1;
			}
		}

		$response = $response->withJson($result,200);
		return $response;
	}

	public function deleteGroupRequest($request,$response,$args){
		$id_request = $args["id_request"];

		$group_request_mapper = new GroupRequestMapper($this->db);
		$result = $group_request_mapper->deleteGroupRequest($id_request);

		$response = $response->withJson($result,200);
		return $response;
	}
}