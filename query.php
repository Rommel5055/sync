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

$today = time();
list ( $sqlin, $para ) = $DB->get_in_or_equal ($today);

$sql = "SELECT sc.catid,
        count(sc.id) AS ncourses
        FROM {sync_course} AS sc
        INNER JOIN {course} as c ON (sc.id = c.id)
        WHERE c.id > 0 AND c.enddate > $sqlin
        Group By sc.catid";
$results = $DB->get_records_sql($sql, $para);


//var_dump($results);
echo "<table border = 1>
        <tr>
        <th>catid</th>
        <th>ncourses</th>
        </tr>
        ";
foreach ($results as $row){
    echo "<tr>";
    echo "<td>". $row->catid."</td>";
    echo "<td>". $row->ncourses."</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
echo"###################################################################################################<br>";
