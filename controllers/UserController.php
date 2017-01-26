<?php

class UserController extends Controller{

	public function getUserById($request,$response,$args){
		$id = $args["id"];

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->getUserById($id);

		$response = $response->withJson($result,200);

		return $response;
	}

	public function getUserByEmail($request,$response,$args){
		$email = $args["email"];

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->getUserByEmail($email);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function searchUsers($request,$response,$args){
		$query = $args["query"];

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->getUsersByDisplayName($query);

		$response = $response->withJson($result,200);

		return $response;
	}

	public function insertUser($request,$response){
		$params = $request->getParsedBody();

		$user_mapper = new UserMapper($this->db);
		$user_exists = $user_mapper->emailExists($params["email"]);
		$result = null;

		if($user_exists){
			$result = $user_mapper->getUserByEmail($params["email"]);
		}
		else{
			$insert_id = $user_mapper->insertUser($params);
			$result = $user_mapper->getUserById($insert_id);
		}
		$response = $response->withJson($result,200);
		return $response;
	}

	public function emailExists($request,$response,$args){
		$email = $args["email"];

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->emailExists($email);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function updateUser($request,$response,$args){
		$id = $args["id"];
		$params = $request->getParsedBody();

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->updateUser($id,$params);

		$response = $response->withJson($result,200);
		return $response;
	}

	public function getRelationship($request,$response,$args){
		$id_user1 = $args["id_user1"];
		$id_user2 = $args["id_user2"];

		$user_mapper = new UserMapper($this->db);
		$result = $user_mapper->getRelationship($id_user1,$id_user2);

		$response = $response->withJson($result,200);
		return $response;
	}
}