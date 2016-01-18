<?php

	abstract class PageNotFoundView extends PageView{
		static protected $_data = array();
		static protected $_accessLevel = 0;
		
		static protected $_templateFile = 'pages/404.html';
		
	}