<?php

	class ResponseObject {
		
		const ERROR_NO_WHITELABEL_PROGRAM_FOUND = 127;
	
		public $data = array();
		public $message = 'Success!';
		public $code = 0;
	
		public function __construct($data = array(), $message = 'Success!', $code = 0, $wrap = true){
			$this->data = $data;
			$this->message = $message;
			$this->code = $code;
			$this->wrap = $wrap;
		}
	}