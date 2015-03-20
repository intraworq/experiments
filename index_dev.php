<?php
//example filtering access to development mode.
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
	echo 'Access denied';
}

include 'src/IntraworQ/index_dev.php';