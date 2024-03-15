<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.
// Produced by the UNIMOODLE University Group: Universities of
// Valladolid, Complutense de Madrid, UPV/EHU, León, Salamanca,
// Illes Balears, Valencia, Rey Juan Carlos, La Laguna, Zaragoza, Málaga,
// Córdoba, Extremadura, Vigo, Las Palmas de Gran Canaria y Burgos.

/**
 * Version details
 *
 * @package    notificationscondition_sessionend
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_notificationsagent\notificationsagent;
use local_notificationsagent\notificationplugin;

class notificationscondition_sessionend_observer {

    /**
     * @throws \dml_exception
     */
    public static function course_viewed(\core\event\course_viewed $event) {
        if (!isloggedin() || $event->courseid == 1) {
            return;
        }

        $userid = $event->userid;
        $courseid = $event->courseid;
        $timeaccess = $event->timecreated;

        $pluginname = 'sessionend';

        $conditions = notificationsagent::get_conditions_by_course($pluginname, $courseid);

        foreach ($conditions as $condition) {
            $decode = $condition->parameters;
            $pluginname = $condition->pluginname;
            $condtionid = $condition->id;
            $param = json_decode($decode, true);
            $cache = $timeaccess + $param[notificationplugin::UI_TIME];
            if (!notificationsagent::was_launched_indicated_times(
                $condition->ruleid, $condition->ruletimesfired, $courseid, $userid
            )
            ) {
                notificationsagent::set_timer_cache($userid, $courseid, $cache, $pluginname, $condtionid, true);
                notificationsagent::set_time_trigger($condition->ruleid, $condtionid, $userid, $courseid, $cache);
            }
        }
    }
}
