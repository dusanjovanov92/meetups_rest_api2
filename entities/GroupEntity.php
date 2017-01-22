<?php

class GroupEntity implements JsonSerializable{
	protected $id;
	protected $name;
	protected $admin;
	protected $member_count;
	protected $members;
	protected $next_meeting;
	protected $current_meeting_count;
	protected $meetings;

	public function __construct($id = null,$name = null,$admin = null,$member_count = null,$members = null,$next_meeting = null,$current_meeting_count = null,
		$meetings = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->admin = $admin;
		$this->member_count = $member_count;
		$this->members = $members;
		$this->next_meeting = $next_meeting;
		$this->current_meeting_count = $current_meeting_count;
		$this->meetings = $meetings;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
	    return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
	    return $this->name;
	}

	/**
	 * @return object
	 */
	public function getAdmin()
	{
	    return $this->admin;
	}

	/**
	 * @return integer
	 */
	public function getMemberCount()
	{
	    return $this->member_count;
	}

	/**
	 * @return array
	 */
	public function getMembers()
	{
	    return $this->members;
	}

	/**
	 * @return object
	 */
	public function getNextMeeting()
	{
	    return $this->next_meeting;
	}

	/**
	 * @return integer
	 */
	public function getCurrentMeetingCount()
	{
	    return $this->current_meeting_count;
	}

	/**
	 * @return array
	 */
	public function getMeeting()
	{
	    return $this->meetings;
	}


	public function jsonSerialize()
	{
		return ["id"=>$this->id,
			   "name"=>$this->name,
			   "admin"=>$this->admin,
			   "memberCount"=>$this->member_count,
			   "members"=>$this->members,
			   "nextMeeting"=>$this->next_meeting,
			   "currentMeetingCount"=>$this->current_meeting_count,
			   "meetings"=>$this->meetings];
	}
}