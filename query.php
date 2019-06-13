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

list ( $sqlin, $par ) = 'student';

$query = "SELECT c.id,
        count(u.id) AS nstudents,
        c.fullname,
        c.shortname
        FROM mdl_course AS c
        INNER JOIN mdl_context AS ct ON c.id = ct.instanceid
        INNER JOIN mdl_role_assignments AS ra ON ra.contextid = ct.id
        INNER JOIN mdl_user AS u ON u.id = ra.userid
        INNER JOIN mdl_role AS r ON r.id = ra.roleid
        WHERE c.id > 0 AND r.archetype $sqlin
        Group By c.id";
$results = $DB->get_records_sql($query, $par);

echo "<table border = 1>
        <tr>
        <th>cid</th>
        <th>nstudents</th>
        <th>fullname</th>
        <th>shortname</th>
        </tr>
        ";
foreach ($results as $row){
    echo "<tr>";
    echo "<td>". $row->cid."</td>";
    echo "<td>". $row->nstudents."</td>";
    echo "<td>". $row->fullname."</td>";
    echo "<td>". $row->shortname."</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
