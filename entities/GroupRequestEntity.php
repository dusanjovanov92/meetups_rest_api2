<?php

class GroupRequestEntity implements JsonSerializable{

	protected $group;

	public function __construct($group){
		$this->group = $group;
	}

	/**
	 * @return object
	 */
	public function getGrou()
	{
	    return $this->group;
	}

	public function jsonSerialize()
	{
		return ["group"=>$this->group];
	}
}