<?php

class GroupRequestEntity implements JsonSerializable{

	protected $id;
	protected $group;

	public function __construct($id,$group){
		$this->id = $id;
		$this->group = $group;
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
	public function getGrou()
	{
	    return $this->group;
	}

	public function jsonSerialize()
	{
		return ["id"=>$this->id,
				"group"=>$this->group];
	}
}