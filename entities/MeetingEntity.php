<?php

class MeetingEntity implements JsonSerializable{
	protected $id;
    protected $id_group;
    protected $label;
    protected $start_time;
    protected $firebase_node;

    public function __construct($row){
    	$this->id = $row["id"];
    	$this->id_group = $row["id_group"];
    	$this->label = $row["label"];
    	$this->start_time = $row["start_time"];
    	$this->firebase_node = $row["firebase_node"];
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
    public function getIdGroup()
    {
        return $this->id_group;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return integer
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @return string
     */
    public function getFireBaseNode()
    {
        return $this->firebase_node;
    }

    public function jsonSerialize()				
    {
        return ["id" => $this->id,
                "idGroup" => $this->id_group,
                "label" => $this->label,
                "startTime" => $this->start_time,
                "firebaseNode" => $this->firebase_node]; 
    }
}