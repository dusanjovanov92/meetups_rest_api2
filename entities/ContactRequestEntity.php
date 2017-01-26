<?php

class ContactRequestEntity implements JsonSerializable{

	protected $id;
	protected $user;

	public function __construct($id,$user){
		$this->id = $id;
		$this->user = $user;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
	    return $this->id;
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
		return ["id"=>$this->id,
				"user"=>$this->user];
	}
}