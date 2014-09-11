<?php 
function get_uid() {
	return isset($_SESSION['id']) && $_SESSION['id'] ? $_SESSION['id'] : null;
}

function user_exists_online() {
	return true;	
}

function get_cached_config($name) {
	return '';
}
 ?>