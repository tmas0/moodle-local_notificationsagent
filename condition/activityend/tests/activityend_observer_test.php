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

// Project implemented by the "Recovery, Transformation and Resilience Plan.
// Funded by the European Union - Next GenerationEU\".
//
// Produced by the UNIMOODLE University Group: Universities of
// Valladolid, Complutense de Madrid, UPV/EHU, León, Salamanca,
// Illes Balears, Valencia, Rey Juan Carlos, La Laguna, Zaragoza, Málaga,
// Córdoba, Extremadura, Vigo, Las Palmas de Gran Canaria y Burgos.

/**
 * Version details
 *
 * @package    notificationscondition_activityend
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace notificationscondition_activityend;

use Generator;
use local_notificationsagent\notificationsagent;
use local_notificationsagent\rule;
use notificationscondition_activityend\activityend;

/**
 * Testing activityend observer
 *
 * @group notificationsagent
 */
class activityend_observer_test extends \advanced_testcase {
    /**
     * @var rule
     */
    private static $rule;
    /**
     * @var \stdClass
     */
    private static $user;
    /**
     * @var \stdClass
     */
    private static $course;

    /**
     * Date start for the course
     */
    public const COURSE_DATESTART = 1704099600; // 01/01/2024 10:00:00.
    /**
     * Date end for the course
     */
    public const COURSE_DATEEND = 1706605200; // 30/01/2024 10:00:00,
    /**
     * Activity date start
     */
    public const CM_DATESTART = 1704099600; // 01/01/2024 10:00:00,
    /**
     * Activity date end
     */
    public const CM_DATEEND = 1705741200; // 20/01/2024 10:00:00,
    /**
     * User first access to a course
     */
    public const USER_FIRSTACCESS = 1704099600;
    /**
     * User last access to a course
     */
    public const USER_LASTACCESS = 1704099600;

    final public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        $rule = new rule();
        self::$rule = $rule;

        self::$course = self::getDataGenerator()->create_course(
            ([
                        'startdate' => self::COURSE_DATESTART,
                        'enddate' => self::COURSE_DATEEND,
                ])
        );

        $role = 'student';
        $arguments = $this->getProvidedData();
        foreach ($arguments as $key => $value) {
            $$key = $value;
        }
        self::$user = self::getDataGenerator()->create_and_enrol(self::$course, $role);
    }

    /**
     * Testing course module updated
     *
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     * @covers       \notificationscondition_activityend_observer::course_module_updated
     * @dataProvider dataprovidercoursemoduleupdated
     */

    public function test_course_module_updated($role) {
        global $DB, $USER;

        \uopz_set_return('time', self::COURSE_DATEEND - 120);
        $quizgenerator = self::getDataGenerator()->get_plugin_generator('mod_quiz');
        $cmgen = $quizgenerator->create_instance([
                'course' => self::$course->id,
        ]);
        $cmgen->timeclose = self::COURSE_DATEEND;

        $dataform = new \StdClass();
        $dataform->title = "Rule Test";
        $dataform->type = 1;
        $dataform->courseid = self::$course->id;
        $dataform->timesfired = 2;
        $dataform->runtime_group = ['runtime_days' => 5, 'runtime_hours' => 0, 'runtime_minutes' => 0];
        $USER->id = self::$user->id;
        $ruleid = self::$rule->create($dataform);
        self::$rule->set_id($ruleid);

        $pluginname = activityend::NAME;
        $objdb = new \stdClass();
        $objdb->ruleid = self::$rule->get_id();
        $objdb->courseid = self::$course->id;
        $objdb->type = 'condition';
        $objdb->pluginname = $pluginname;
        $objdb->parameters = '{"cmid":' . $cmgen->cmid . ',"time":"86400"}';
        $objdb->cmid = $cmgen->cmid;
        // Insert.
        $conditionid = $DB->insert_record('notificationsagent_condition', $objdb);
        $this->assertIsInt($conditionid);

        self::$rule::create_instance($ruleid);
        self::setUser(self::$user->id);

        $event = \core\event\course_module_updated::create([
                'context' => \context_module::instance($cmgen->cmid),
                'userid' => self::$user->id,
                'courseid' => self::$course->id,
                'objectid' => $cmgen->cmid,
                'other' => [
                        'modulename' => 'quiz',
                        'instanceid' => $cmgen->id,
                        'name' => $cmgen->name,
                ],
        ]);

        $event->trigger();

        $cache = $DB->get_record('notificationsagent_cache', ['conditionid' => $conditionid]);
        $trigger = $DB->get_record('notificationsagent_triggers', ['conditionid' => $conditionid]);

        $expected = $role == 'student' ? self::$user->id : notificationsagent::GENERIC_USERID;
        $this->assertEquals($pluginname, $cache->pluginname);
        $this->assertEquals(self::$course->id, $cache->courseid);
        $this->assertEquals($expected, $cache->userid);

        $this->assertEquals(self::$course->id, $trigger->courseid);
        $this->assertEquals(self::$rule->get_id(), $trigger->ruleid);
        $this->assertEquals($expected, $trigger->userid);

        \uopz_unset_return('time');
    }

    /**
     * Data provider for course_module_updated.
     */
    public static function dataprovidercoursemoduleupdated(): Generator {
        yield ['role' => 'student'];
        yield ['role' => 'editingteacher'];
    }
}
