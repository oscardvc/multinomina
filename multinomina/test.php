<?php
	include_once('connection.php');
	$conn = new Connection();
	$result = $conn->query("SELECT EVENT_NAME FROM information_schema.EVENTS");

	while(list($event) = $conn->fetchRow($result))
		echo "DROP EVENT IF EXISTS $event;<br/>";
?>
