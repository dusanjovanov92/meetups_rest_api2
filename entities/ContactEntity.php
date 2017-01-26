<?php

class ContactEntity implements JsonSerializable{

	protected $user;
	protected $firebase_node;

	public function __construct($user,$firebase_node)
	{
		$this->user = $user;
		$this->firebase_node = $firebase_node;
	}

	/**
	 * @return object
	 */
	public function getUser()
	{
	    return $this->user;
	}

	/**
	 * @return string
	 */
	public function getFirebaseNode()
	{
	    return $this->firebase_node;
	}

	public function jsonSerialize()
	{
		return ["user"=>$this->user,
				"firebaseNode"=>$this->firebase_node];
	}
}