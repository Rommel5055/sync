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
foreach ($mails as $mail){
    $usercfg = $DB->get_records_sql('Select id,
                                            firstname,
                                            lastname,
                                            email
                                            From {user} where email == ?', array([$mail]));


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
    echo "<td>". $row->email."</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
?>