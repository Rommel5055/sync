<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
*
* @package    local
* @subpackage sync
* @copyright  2019 javier Gonzalez (javergonzalez@alumnos.uai.cl)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

//define('CLI_SCRIPT', true);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");
require_once($CFG->dirroot . "/local/sync/locallib.php");
require_once ($CFG->libdir . '/clilib.php');

global $DB, $CFG;

// Now get cli options
list($options, $unrecognized) = cli_get_params(array(
		'help' => false,
		'debug' => false,
), array(
		'h' => 'help',
		'd' => 'debug'
));
if($unrecognized) {
	$unrecognized = implode("\n  ", $unrecognized);
	cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}
// Text to the sync console
if($options['help']) {
	$help =
	// Todo: localize - to be translated later when everything is finished
	"Sync Omega to get the courses and users (students and teachers).
	Options:
	-h, --help            Print out this help
	Example:
	\$sudo /usr/bin/php /local/sync/cli/tester.php";
	echo $help;
	die();
}
//heading
cli_heading('Check'); // TODO: localize
echo "\nStarting at ".date("F j, Y, G:i:s")."\n";

$mails = explode("," ,$CFG->sync_mailalert);
$userlist = array();
foreach ($mails as $mail){
    $usercfg = $DB->get_records_sql('Select * From {user} where email = ?', array([$mail]));
    $userlist[] = $usercfg;
    var_dump($usercfg);
}

$empty = sync_emptycourses();
if (empty($empty)){
    echo "\nNo course came empty. All is good.\n";   
}
else{
    echo "\nThere are empty courses!\nFor more information check your email.\n";
    $case = "sync_emptycourses";
    foreach ($userlist as $mail){
        var_dump($mail);
        sync_sendmail($mail, $case, $empty);
    }
}

$empty = sync_emptysyncenrol();
if (empty($empty)){
    echo "\nNo course came empty. All is good.\n";
}
else{
    echo "\nThere are empty courses!\nFor more information check your email.\n";
    $case = "sync_emptysynccourses";
    foreach ($userlist as $mail){
        sync_sendmail($mail, $case, $empty);
    }
}

$empty = sync_emptysynccourses();
if (empty($empty)){
    echo "\nNo sync category came empty. All is good.\n";
}
else{
    echo "\nThere are empty courses!\nFor more information check your email.\n";
    $case = "sync_emptysynccat";
    foreach ($userlist as $mail){
        sync_sendmail($mail, $case, $empty);
    }
}

if($CFG->sync_execcommand != NULL){
    exec($CFG->sync_execcommand);
}
exit(0);

