<?php

class ContactRequestEntity implements JsonSerializable{

	protected $user;

	public function __construct($user){
		$this->user = $user;
	}

	/**
	 * @return object
	 */
	public function getUser()
	{
	    return $this->user;
	}

	public function jsonSerialize()
	{
		return ["user"=>$this->user];
	}
}