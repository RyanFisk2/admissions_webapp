<?php
	// For random utility functions that should be available for access in all files  - can just include this file in the header
	require_once('connectvars.php');

	if (!isset($_SESSION)) {
		session_start();
	}

	function make_url($file, $query) {
      return 'http://' . $_SERVER["HTTP_HOST"] . make_rel_url($file, $query);
    }
    function make_rel_url($file, $query) {
      return url($file) . '?' . http_build_query($query);
    }
    function get_path_file() {
    	$path = explode('/', $_SERVER["SCRIPT_NAME"]); 
    	return $path[count($path) - 1];
    }
    function url($file) {
      return dirname($_SERVER["PHP_SELF"]) . '/' . $file;
    }
    function try_insert($dbc, $query, $success) {
	    if (mysqli_query($dbc, $query)) {
		    echo "in outer if";
		    if (!is_null($success)) echo "Success: " . $success . '<br>';
		    echo $dbc->insert_id;
        	return $dbc->insert_id;
	    }else if (mysqli_connect_errno()){
		    echo "Failed to connect to DB";
	    }
      	    else throw new Exception('Error with query ' . $query . ': ' . mysqli_error($dbc));
    }
    function try_query($dbc, $query, $success) {
      if ($result = mysqli_query($dbc, $query)) {
        if (!is_null($success)) echo "Success: " . $success . '<br>';
        return $result;
      }
      else throw new Exception('Error with query ' . $query . ': ' . mysqli_error($dbc));
      return null;
    }
?>
