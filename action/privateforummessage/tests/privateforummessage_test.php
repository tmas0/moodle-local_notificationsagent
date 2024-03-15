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
// Funded by the European Union - Next GenerationEU".
//
// Produced by the UNIMOODLE University Group: Universities of
// Valladolid, Complutense de Madrid, UPV/EHU, León, Salamanca,
// Illes Balears, Valencia, Rey Juan Carlos, La Laguna, Zaragoza, Málaga,
// Córdoba, Extremadura, Vigo, Las Palmas de Gran Canaria y Burgos.

/**
 * Version details
 *
 * @package    notificationsaction_privateforummessage
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace notificationsaction_privateforummessage;

use Generator;
use local_notificationsagent\evaluationcontext;
use local_notificationsagent\form\editrule_form;
use local_notificationsagent\notificationplugin;
use local_notificationsagent\helper\test\phpunitutil;
use local_notificationsagent\rule;
use notificationsaction_privateforummessage\privateforummessage;

/**
 * @group notificationsagent
 */
class privateforummessage_test extends \advanced_testcase {

    /**
     * @var rule
     */
    private static $rule;
    private static $subplugin;

    /**
     * @var \stdClass
     */
    private static $coursetest;
    /**
     * @var string
     */
    private static $subtype;
    /**
     * @var \stdClass
     */
    private static $user;
    /**
     * @var evaluationcontext
     */
    private static $context;
    /**
     * @var bool|\context|\context_course
     */
    private static $coursecontext;
    /**
     * @var array|string[]
     */
    private static $elements;
    /**
     * id for condition
     */
    public const CONDITIONID = 1;
    /**
     * Date start for the course
     */
    public const COURSE_DATESTART = 1704099600; // 01/01/2024 10:00:00.
    /**
     * Date end for the course
     */
    public const COURSE_DATEEND = 1706605200; // 30/01/2024 10:00:00,

    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        self::$rule = new rule();

        self::$subplugin = new privateforummessage(self::$rule);
        self::$coursetest = self::getDataGenerator()->create_course(
            ['startdate' => self::COURSE_DATESTART, 'enddate' => self::COURSE_DATEEND]
        );
        self::$coursecontext = \context_course::instance(self::$coursetest->id);
        self::$user = self::getDataGenerator()->create_user();
        self::$context = new evaluationcontext();
        self::$context->set_userid(self::$user->id);
        self::$context->set_courseid(self::$coursetest->id);
        self::$subtype = 'privateforummessage';
        self::$elements = ['[FFFF]', '[TTTT]', '[BBBB]'];
    }

    /**
     *
     * @param string $param
     *
     * @covers       \notificationsaction_privateforummessage\privateforummessage::execute_action
     *
     * @dataProvider dataprovider
     */
    public function test_execute_action($param, $creatediscussion) {
        global $DB;

        // Simulate data from form.
        $dataform = new \StdClass();
        $dataform->title = "Rule Test";
        $dataform->type = 1;
        $dataform->courseid = self::$coursetest->id;
        $dataform->timesfired = 2;
        $dataform->runtime_group = ['runtime_days' => 5, 'runtime_hours' => 0, 'runtime_minutes' => 0];

        $cmgenerator = self::getDataGenerator()->get_plugin_generator('mod_forum');
        $cmtestapf = $cmgenerator->create_instance([
            'course' => self::$coursetest->id,
        ]);

        $auxarray = json_decode($param, true);
        $auxarray['cmid'] = $cmtestapf->cmid;
        $auxarray['time'] = 8460;
        $param = json_encode($auxarray);

        $ruleid = self::$rule->create($dataform);
        // Conditions.
        $DB->insert_record(
            'notificationsagent_condition',
            [
                'ruleid' => $ruleid, 'type' => 'condition', 'complementary' => notificationplugin::COMPLEMENTARY_CONDITION,
                'parameters' => $param,
                'pluginname' => 'forumnoreply', 'cmid' => $cmtestapf->cmid,
            ],
        );
        $actionparam = '{"title":"titulo", "message": "forumbody test", "cmid":' . $cmtestapf->cmid . '}';

        $DB->insert_record(
            'notificationsagent_action',
            [
                'pluginname' => self::$subtype, 'type' => 'action',
                'parameters' => $actionparam,
                'cmid' => $cmtestapf->cmid,
            ],
        );

        if ($creatediscussion) {
            $record = new \stdClass();
            $record->course = self::$coursetest->id;
            $record->userid = self::$user->id;
            $record->usermodified = self::$user->id;
            $record->forum = $cmtestapf->id;
            $record->timeend = 0;
            $record->timemodified = self::COURSE_DATESTART;
            $record->timestart = 1;

            $discussion = self::getDataGenerator()->get_plugin_generator('mod_forum')->create_discussion($record);
            $record->discussion = $discussion->id;
        }

        $instance = self::$rule::create_instance($ruleid);

        self::$context->set_params($param);
        self::$context->set_userid(self::$user->id);
        self::$subplugin->set_id(self::CONDITIONID);
        self::$context->set_rule($instance);
        self::$context->set_usertimesfired(1);

        // Test action.
        $result = self::$subplugin->execute_action(self::$context, $actionparam);

        if ($creatediscussion) {
            $this->assertIsInt($result);
            $this->assertGreaterThan(0, $result);
        } else {
            $this->assertFalse($result);
        }
    }

    public static function dataprovider(): Generator {
        yield ['{"title":"TEST","message":"Message body {user_name}"}', true];
        yield ['{"title":"TEST","message":"Message body {user_name}"}', false];
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::get_subtype
     */
    public function test_getsubtype() {
        $this->assertSame(self::$subtype, self::$subplugin->get_subtype());
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::is_generic
     */
    public function test_isgeneric() {
        $this->assertFalse(self::$subplugin->is_generic());
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::get_elements
     */
    public function test_getelements() {
        $this->assertSame(self::$elements, self::$subplugin->get_elements());
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::get_title
     */
    public function test_gettitle() {
        $this->assertNotNull(self::$subplugin->get_title());
        foreach (self::$elements as $element) {
            $this->assertStringContainsString($element, self::$subplugin->get_title());
        }
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::check_capability
     */
    public function test_checkcapability() {
        $this->assertSame(
            has_capability('local/notificationsagent:' . self::$subtype, self::$coursecontext),
            self::$subplugin->check_capability(self::$coursecontext)
        );
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::convert_parameters
     */
    public function test_convert_parameters() {
        $params = [
            "5_privateforummessage_title" => "Test title", "5_privateforummessage_message" => ['text' => "Message body"],
            "5_privateforummessage_cmid" => "7",
        ];
        $expected = '{"title":"Test title","message":{"text":"Message body"},"cmid":"7"}';
        $method = phpunitutil::get_method(self::$subplugin, 'convert_parameters');
        $result = $method->invoke(self::$subplugin, 5, $params);
        $this->assertSame($expected, $result);
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::get_ui
     */
    public function test_getui() {
        $courseid = self::$coursetest->id;
        $ruletype = rule::RULE_TYPE;
        $typeaction = "add";
        $customdata = [
            'ruleid' => self::$rule->get_id(),
            notificationplugin::TYPE_CONDITION => self::$rule->get_conditions(),
            notificationplugin::TYPE_EXCEPTION => self::$rule->get_exceptions(),
            notificationplugin::TYPE_ACTION => self::$rule->get_actions(),
            'type' => $ruletype,
            'timesfired' => rule::MINIMUM_EXECUTION,
            'courseid' => $courseid,
            'getaction' => $typeaction,
        ];

        $form = new editrule_form(new \moodle_url('/'), $customdata);
        $form->definition();
        $form->definition_after_data();
        $mform = phpunitutil::get_property($form, '_form');
        $id = time();
        $subtype = notificationplugin::TYPE_CONDITION;
        self::$subplugin->get_ui($mform, $id, $courseid, $subtype);

        $method = phpunitutil::get_method(self::$subplugin, 'get_name_ui');
        $uititlename = $method->invoke(self::$subplugin, $id, self::$subplugin::UI_TITLE);
        $uiamessagename = $method->invoke(self::$subplugin, $id, self::$subplugin::UI_MESSAGE);
        $uiactivityname = $method->invoke(self::$subplugin, $id, self::$subplugin::UI_ACTIVITY);

        $this->assertTrue($mform->elementExists($uititlename));
        $this->assertTrue($mform->elementExists($uiamessagename));
        $this->assertTrue($mform->elementExists($uiactivityname));
    }

    /**
     * @covers       \notificationsaction_privateforummessage\privateforummessage::get_parameters_placeholders
     *
     * @dataProvider dataprovider2
     */
    public function test_getparametersplaceholders($param) {
        $cmgenerator = self::getDataGenerator()->get_plugin_generator('mod_forum');
        $cmtestaf = $cmgenerator->create_instance([
            'course' => self::$coursetest->id,
        ]);

        $auxarray = json_decode($param, true);
        // Add cmid.
        $auxarray['cmid'] = $cmtestaf->cmid;
        $param = json_encode($auxarray);

        // Format message text // delete ['text'].
        $auxarray['message'] = $auxarray['message']['text'];

        self::$subplugin->set_parameters($param);
        $actual = self::$subplugin->get_parameters_placeholders();

        $this->assertSame(json_encode($auxarray), $actual);
    }

    public static function dataprovider2(): Generator {
        $data['title'] = 'TEST';
        $data['message']['text'] = 'Message body';
        yield [json_encode($data)];
        $data['title'] = 'TEST2';
        $data['message']['text'] = 'Message body';
        yield [json_encode($data)];
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::process_markups
     *
     */
    public function test_processmarkups() {
        $UI_TITLE = 'test title';
        $UI_MESSAGE = 'test message';

        $quizgenerator = self::getDataGenerator()->get_plugin_generator('mod_forum');
        $cmgen = $quizgenerator->create_instance([
            'course' => self::$coursetest->id,
        ]);
        $params[self::$subplugin::UI_ACTIVITY] = $cmgen->cmid;
        $params[self::$subplugin::UI_TITLE] = $UI_TITLE;
        $params[self::$subplugin::UI_MESSAGE]['text'] = $UI_MESSAGE;
        $params = json_encode($params);

        $paramstoreplace = [
            shorten_text($cmgen->name),
            shorten_text(str_replace('{' . rule::SEPARATOR . '}', ' ', $UI_TITLE)),
            shorten_text(format_string(str_replace('{' . rule::SEPARATOR . '}', ' ', $UI_MESSAGE))),
        ];
        $expected = str_replace(self::$subplugin->get_elements(), $paramstoreplace, self::$subplugin->get_title());

        self::$subplugin->set_parameters($params);
        $content = [];
        self::$subplugin->process_markups($content, self::$coursetest->id);
        $this->assertSame([$expected], $content);
    }

    /**
     * @covers \notificationsaction_privateforummessage\privateforummessage::forum_add_post
     * @return void
     */
    public function test_forum_add_post() {
        global $DB;
        $cmgenerator = self::getDataGenerator()->get_plugin_generator('mod_forum');
        $cmtestfap = $cmgenerator->create_instance([
            'course' => self::$coursetest->id,
        ]);

        $record = new \stdClass();
        $record->course = self::$coursetest->id;
        $record->userid = self::$user->id;
        $record->usermodified = self::$user->id;
        $record->forum = $cmtestfap->id;
        $record->timeend = 0;
        $record->timemodified = self::COURSE_DATESTART;
        $record->timestart = 1;

        $discussion = self::getDataGenerator()->get_plugin_generator('mod_forum')->create_discussion($record);
        $record->discussion = $discussion->id;

        $obj = new \stdClass();
        $obj->discussion = $discussion->id;
        $obj->parent = $discussion->firstpost;
        $obj->privatereplyto = self::$user->id;
        $obj->userid = get_admin()->id;
        $obj->created = time();
        $obj->modified = time();
        $obj->mailed = FORUM_MAILED_PENDING;
        $obj->subject = 'Post subject';
        $obj->message = 'Post message';
        $obj->forum = $cmtestfap->id;
        $obj->course = self::$coursetest->id;

        $this->assertIsInt(privateforummessage::forum_add_post($obj));

    }

}

