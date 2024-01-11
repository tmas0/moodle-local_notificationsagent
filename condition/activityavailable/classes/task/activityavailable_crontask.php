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
// Project implemented by the \"Recovery, Transformation and Resilience Plan.
// Funded by the European Union - Next GenerationEU\".
//
// Produced by the UNIMOODLE University Group: Universities of
// Valladolid, Complutense de Madrid, UPV/EHU, León, Salamanca,
// Illes Balears, Valencia, Rey Juan Carlos, La Laguna, Zaragoza, Málaga,
// Córdoba, Extremadura, Vigo, Las Palmas de Gran Canaria y Burgos.

/**
 * Version details
 *
 * @package    local_notificationsagent
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace notificationscondition_activityavailable\task;
defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../../../../notificationsagent.php');
require_once(__DIR__ .'/../../../../classes/engine/notificationsagent_engine.php');
require_once(__DIR__ . '/../../../../lib.php');

use core\task\scheduled_task;
use notificationsagent\notificationsagent;
use Notificationsagent_engine;

class activityavailable_crontask extends scheduled_task {

    /**
     * Get a descriptive name for the task (shown to admins)
     *
     * @return string
     */
    public function get_name() {
        return get_string('activityavailable_crontask', 'notificationscondition_activityavailable');
    }

    /**
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        custom_mtrace("Activityavailable start");

        $pluginname = 'activityavailable';
        $conditions = notificationsagent::get_conditions_by_plugin($pluginname);

        foreach ($conditions as $condition) {
            $courseid = $condition->courseid;
            $context = \context_course::instance($courseid);
            $enrolledusers = notificationsagent::get_usersbycourse($context);
            foreach ($enrolledusers as $enrolleduser) {
                if (!notificationsagent::was_launched_indicated_times(
                    $condition->ruleid, $condition->ruletimesfired, $courseid, $enrolleduser->id)) {
                        Notificationsagent_engine::notificationsagent_engine_evaluate_rule(
                        [$condition->ruleid], time(), $enrolleduser->id, $courseid);
                }
            }
        }
        custom_mtrace("Activityavailable end ");
    }
}

