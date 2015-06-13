<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Right {
	private $rightId;
	private $rightCode;
	private $rightDescription;
	/**
	 * @return the $rightId
	 */
	public function getRightId() {
		return $this->rightId;
	}

	/**
	 * @return the $rightCode
	 */
	public function getRightCode() {
		return $this->rightCode;
	}

	/**
	 * @return the $rightDescription
	 */
	public function getRightDescription() {
		return $this->rightDescription;
	}

	/**
	 * @param field_type $rightId
	 */
	public function setRightId($rightId) {
		$this->rightId = $rightId;
	}

	/**
	 * @param field_type $rightCode
	 */
	public function setRightCode($rightCode) {
		$this->rightCode = $rightCode;
	}

	/**
	 * @param field_type $rightDescription
	 */
	public function setRightDescription($rightDescription) {
		$this->rightDescription = $rightDescription;
	}

	

}

?>