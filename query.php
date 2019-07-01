<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->dirroot . '/local/sync/locallib.php');
global $DB, $CFG;
//Deleted 3 in role ID, so it just takes teachers

require_login();
if (isguestuser()) {
    print_error('Fucking Error');
    die();
}

$mails = explode("," ,$CFG->sync_mailalert);
var_dump($mails);
list ( $sqlin, $para ) = $DB->get_in_or_equal ($mails);
foreach ($mails as $mail){
    $results = $DB->get_records_sql("Select id,
                                            firstname,
                                            lastname,
                                            username
                                            From {user} where username $sqlin
                                            OR email $sqlin", $para);
}


//var_dump($results);
echo "<table border = 1>
        <tr>
        <th>id</th>
        <th>firstname</th>
        <th>lastname</th>
        <th>email</th>
        </tr>
        ";
foreach ($results as $row){
    echo "<tr>";
    echo "<td>". $row->id."</td>";
    echo "<td>". $row->firstname."</td>";
    echo "<td>". $row->lastname."</td>";
    echo "<td>". $row->username."</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";

echo "danger danger";
$results = $DB->get_records_sql("Select * from {user} where lastname = 'queirolo'");
foreach ($results as $res){
    var_dump($res);
    echo "\n";
}
?>