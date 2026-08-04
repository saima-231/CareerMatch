<?php

include "database.php";


$stmt = $pdo->prepare("

UPDATE internships

SET status='Inactive'

WHERE deadline < CURDATE()

AND status='Active'

");


$stmt->execute();
