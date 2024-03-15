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
 * @package    notificationscondition_activityavailable
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace notificationscondition_activityavailable;

use local_notificationsagent\notificationacplugin;
use local_notificationsagent\notificationconditionplugin;
use local_notificationsagent\evaluationcontext;

class activityavailable extends notificationconditionplugin {

    /** @var string name */
    public const NAME = 'activityavailable';

    /**
     * Subplugin title
     *
     * @return \lang_string|string
     */
    public function get_title() {
        return get_string('conditiontext', 'notificationscondition_activityavailable');
    }

    /**
     *  Subplugins elements
     *
     * @return string[]
     */
    public function get_elements() {
        return ['[AAAA]'];
    }

    public function get_subtype() {
        return get_string('subtype', 'notificationscondition_activityavailable');
    }

    /** Evaluates this condition using the context variables or the system's state and the complementary flag.
     *
     * @param evaluationcontext $context  |null collection of variables to evaluate the condition.
     *                                    If null the system's state is used.
     *
     * @return bool true if the condition is true, false otherwise.
     */
    public function evaluate(evaluationcontext $context): bool {
        $courseid = $context->get_courseid();
        $userid = $context->get_userid();
        $params = json_decode($context->get_params());
        $cmid = $params->{self::UI_ACTIVITY};

        $available = false;
        if ($cmid && $cm = get_fast_modinfo($courseid)->get_cm($cmid)) {
            $infoclass = new notificationacplugin($courseid, $cm->availability);
            $variable = '';
            $available = $infoclass->is_available($variable, false, $userid);
        }

        return $available;
    }

    /** Estimate next time when this condition will be true. */
    public function estimate_next_time(evaluationcontext $context) {
        return null;
    }

    public function get_ui($mform, $id, $courseid, $type) {
        $this->get_ui_title($mform, $id, $type);

        // Activity.
        $listactivities = [];
        $modinfo = get_fast_modinfo($courseid);
        foreach ($modinfo->get_cms() as $cm) {
            $listactivities[$cm->id] = format_string($cm->name);
        }
        if (empty($listactivities)) {
            $listactivities['0'] = 'AAAA';
        }
        asort($listactivities);

        $element = $mform->createElement(
            'select',
            $this->get_name_ui($id, self::UI_ACTIVITY),
            get_string(
                'editrule_condition_activity', 'notificationscondition_activityavailable',
                ['typeelement' => '[AAAA]']
            ),
            $listactivities
        );

        $mform->insertElementBefore($element, 'new' . $type . '_group');
    }

    public function check_capability($context) {
        return has_capability('local/notificationsagent:activityavailable', $context);
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    protected function convert_parameters($id, $params) {
        $params = (array) $params;
        $activity = $params[$this->get_name_ui($id, self::UI_ACTIVITY)] ?? 0;
        $this->set_parameters(json_encode([self::UI_ACTIVITY => (int) $activity]));
        return $this->get_parameters();
    }

    /**
     * Process and replace markups in the supplied content.
     *
     * This function should handle any markup logic specific to a notification plugin,
     * such as replacing placeholders with dynamic data, formatting content, etc.
     *
     * @param array $content  The content to be processed, passed by reference.
     * @param int   $courseid The ID of the course related to the content.
     * @param mixed $options  Additional options if any, null by default.
     *
     * @return void Processed content with markups handled.
     */
    public function process_markups(&$content, $courseid, $options = null) {
        $jsonparams = json_decode($this->get_parameters());

        // Check if activity is found in course, if is not, return [AAAA].
        $activityname = '[AAAA]';
        $cmid = $jsonparams->{self::UI_ACTIVITY};
        if ($cmid && $mod = get_fast_modinfo($courseid)->get_cm($cmid)) {
            $activityname = $mod->name;
        }

        $paramstoreplace = [$activityname];
        $humanvalue = str_replace($this->get_elements(), $paramstoreplace, $this->get_title());

        $content[] = $humanvalue;
    }

    public function is_generic() {
        return false;
    }

}
