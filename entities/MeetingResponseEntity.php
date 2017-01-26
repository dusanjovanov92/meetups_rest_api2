<?php

class MeetingResponseEntity implements JsonSerializable{
	protected $id;
	protected $id_meeting;
	protected $user;
	protected $response;

	public function __construct($id,$id_meeting,$user,$response){
		$this->id = $id;
		$this->id_meeting = $id_meeting;
		$this->user = $user;
		$this->response = $response;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
	    return $this->id;
	}

	/**
	 * @return integer
	 */
	public function getIdMeeting()
	{
	    return $this->id_meeting;
	}

	/**
	 * @return object
	 */
	public function getUser()
	{
	    return $this->user;
	}

	/**
	 * @return integer
	 */
	public function getResponse()
	{
	    return $this->response;
	}

	public function jsonSerialize()
	{
		return ["id"=>$this->id,
				"idMeeting"=>$this->id_meeting,
				"user"=>$this->user,
				"response"=>$this->response];
	}
}