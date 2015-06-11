<?php

namespace Zz\BackupOMatic\Exception;

trait ProblemReasonMsgFormattingTrait
{
	protected $reason;
	
	public function _construct ( $reason )
	{
		$this->setReason($reason);
		parent::__construct();
		$this->setMessage();
	}
	
	public function setReason ( $reason )
	{
		if ( !is_string($reason) ) {
			throw new \InvalidArgumentException('$reason must be a string in ' . __METHOD__);
		}
		$this->reason = $reason;
		$this->setMessage();
	}
	
	public function setProblem ( $problem )
	{
		if ( !is_string($problem) ) {
			throw new \InvalidArgumentException('$problem must be a string in ' . __METHOD__);
		}
		$this->problem = $problem;
		$this->setMessage();
	}
	
	protected function setMessage ()
	{
		$this->message = $this->problem . ': ' . $this->reason . '.';
	}
}
