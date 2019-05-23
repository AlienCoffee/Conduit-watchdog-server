<?php
	
// Сохранение с форматированием
$str='{"windows":{"tasklist":"some_script2.cmd"},"linux":{"tasklist":"some_other.sh"}}';
echo "<pre>";
echo json_encode(json_decode($str, true), JSON_PRETTY_PRINT);
echo "</pre>";