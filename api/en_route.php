<?php

function getData() {
	$json = file_get_contents('php://input');
    return json_decode($json, TRUE);
}