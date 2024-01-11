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
 * @category   string
 * @copyright  2023 Proyecto UNIMOODLE
 * @author     UNIMOODLE Group (Coordinator) <direccion.area.estrategia.digital@uva.es>
 * @author     ISYC <soporte@isyc.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Agent de notificacions';
// Settings.
$string['settings'] = 'Ajustaments';
$string['disable_user_use'] = 'Deshabiliteu per a l\'usuari';
$string['disable_user_use_desc'] = 'Deshabiliteu l\'ús de l\'agent de notificacions per a l\'usuari';
$string['tracelog'] = 'Trace log';
$string['tracelog_desc'] = 'Trace log. Deshabilitar en llocs en producció';
$string['startdate'] = 'Configuració de dates dactivitat';
$string['startdate_desc'] = 'Usar una línia per cada activitat amb el patró: pluginname|tablename|startdate|startend';

// Subplugins settings.
$string['notificationaction_action'] = 'Subplugins tipus action ';

$string['managenotificationsactionplugins'] = 'Gestionar plugins tipus action';
$string['managenotificationsconditionplugins'] = 'Gestionar plugins tipus condition';

$string['manageactionplugins'] = 'Gestionar plugins action';
$string['manageconditionplugins'] = 'Gestionar plugins condition';

$string['actionplugins'] = 'Plugins tipus action';
$string['conditionplugins'] = 'Plugins tipus condition';

$string['notificationsactionpluginname'] = 'Plugin action';
$string['notificationsconditionpluginname'] = 'Plugin condition';

$string['hideshow'] = 'Amagar/Mostrar';

// Task.
$string['tatasktriggerssk'] = 'Tasca de desencadenadors de notificacions';
$string['menu'] = 'El meu assistent';
$string['heading'] = 'Agent de Notificacions';

// Status Template.
$string['status_active'] = 'Activa';
$string['status_paused'] = 'Pausada';
$string['status_required'] = 'Obligatòria';

// Import Template.
$string['import'] = 'Import';
$string['no_file_selected'] = 'Cap fitxer seleccionat';
$string['import_success'] = 'Regla importada correctament';
$string['import_error'] = 'No s\'ha pogut importar la regla, revisa el fitxer JSON';
$string['no_json_file'] = 'El fitxer no és JSON';

// Export Template.
$string['export'] = 'Exportar';
$string['ruledownload'] = 'Exportar regla com';

// Assign Template.
$string['assign'] = 'Assignar';
$string['type_template'] = 'Plantilla';
$string['type_rule'] = 'Regla';

// Condition plugins.
$string['condition_days'] = 'Dies';
$string['condition_hours'] = 'Hores';
$string['condition_minutes'] = 'Minuts';
$string['condition_seconds'] = 'Segons';

// EditRule.
$string['editrule_newrule'] = 'Nova regla';
$string['editrule_reports'] = 'Informes';
$string['editrule_activaterule'] = 'Activa';
$string['editrule_pauserule'] = 'Pausa';
$string['editrule_editrule'] = 'Edita';
$string['editrule_reportrule'] = 'Informe';
$string['editrule_deleterule'] = 'Esborra';
$string['editrule_newtemplate'] = 'Nova plantilla';
$string['editrule_title'] = 'Títol';
$string['editrule_type'] = 'Tipus de regla';
$string['editrule_usetemplate'] = 'Selecciona';
$string['editrule_sharerule'] = 'Compartir';
$string['editrule_unsharerule'] = 'Descompartir';
$string['editrule_shareallrule'] = 'Compartir tots';
$string['editrule_sharedallrule'] = 'Compartit';
$string['editrule_timesfired'] = 'Nombre de vegades execució';
$string['editrule_runtime'] = 'Periocitat';

// Condition.
$string['editrule_generalconditions'] = 'Condicions generals';
$string['editrule_newcondition'] = 'Nova condició:';
$string['editrule_condition_title_tocloseactivity'] = 'Queda menys de [TTTT] per tancar l\'activitat [AAAA]';
$string['editrule_condition_title_usercompleteactivity'] = 'L\'usuari té completada l\'activitat [AAAA]';
$string['editrule_condition_title_activeactivity'] = 'L\'activitat [AAAA] està disponible';
$string['editrule_condition_title_betweendates'] = 'Estem entre la data [FFFF-1] i [FFFF-2]';
$string['editrule_condition_title_accessforumactivitiescomplete'] = 'Completa totes les activitats per accedir a aquest fòrum [FFFF]';
$string['editrule_condition_title_forumnotanswer'] = 'Un fil obert per l\'usuari al fòrum [FFFF] sense respondre més de [TTTT] temps';

$string['editrule_condition_element_time'] = 'Temps {$a->typeelement}:';
$string['editrule_condition_element_activity'] = 'Activitat {$a->typeelement}:';
$string['editrule_condition_element_date_from'] = get_string('from').' {$a->typeelement}:';
$string['editrule_condition_element_date_to'] = get_string('to').' {$a->typeelement}:';

// Actions.
$string['editrule_newaction'] = 'Nova acció:';
$string['editrule_action_title_individualnotification'] = 'Enviar notificació individual amb títol [TTTT] i missatge [BBBB]';
$string['editrule_action_title_notificationtouser'] = 'Enviar notificació a un usuari concret [UUUU] amb títol [TTTT] i missatge [BBBB]';
$string['editrule_action_title_postgeneralforum'] = 'Publicar un post general al fòrum [FFFF] amb títol [TTTT] i missatge [BBBB]';
$string['editrule_action_title_postprivateforum'] = 'Publicar un post privat al fòrum [FFFF] amb títol [TTTT] i missatge [BBBB]';
$string['editrule_action_title_addusertogroup'] = 'Afegir un usuari a grup [GGGG]';
$string['editrule_action_title_removeuserfromgroup'] = 'Eliminar un usuari d\'un grup [GGGG]';
$string['editrule_action_title_bootstrapnotification'] = 'Notificació bootstrap';

$string['editrule_action_element_title'] = 'Títol {$a->typeelement}:';
$string['editrule_action_element_message'] = 'Missatge {$a->typeelement}:';
$string['editrule_action_element_user'] = 'Usuari {$a->typeelement}:';
$string['editrule_action_element_forum'] = 'Fòrum {$a->typeelement}:';
$string['editrule_action_element_group'] = 'Grup {$a->typeelement}';

$string['subplugintype_notificationsagentaction'] = 'Subplugins action';

// Rule.
$string['rulecancelled'] = 'Regla cancel·lada';
$string['rulesaved'] = 'Regla desada';

// Card content.
$string['cardif'] = 'Si:';
$string['cardunless'] = 'Excepte si:';
$string['cardthen'] = 'Aleshores:';

// Card Condition time.
$string['card_day'] = 'dia';
$string['card_hour'] = 'hora';
$string['card_minute'] = 'minut';
$string['card_second'] = 'segon';

// Status modal.
$string['statustitle'] = '{$a->textstatus} regla {$a->title}';
$string['statuscontent'] = 'Voleu {$a->textstatus} la regla {$a->title}, voleu continuar?';
$string['statuscancel'] = 'Cancel·lar';
$string['statusaccept'] = 'D\'acord';
$string['statusacceptactivated'] = 'Regla activada';
$string['statusacceptpaused'] = 'Regla pausada';

// Delete modal.
$string['deletetitle'] = 'Esborra la {$a->type} {$a->title}';
$string['deletecontent_nocontext'] = 'S\'esborrarà {$a->type} {$a->title}, voleu continuar?';
$string['deletecontent_hascontext'] = 'La {$a->type} {$a->title} que voleu suprimir, està associada a altres contextos, voleu continuar?';
$string['deleteaccept'] = 'Regla esborrada';

// Assign modal.
$string['assignassign'] = 'Assignar: ';
$string['assigncancel'] = 'Cancel·la';
$string['assignsave'] = 'Desa els canvis';
$string['assignforced'] = 'Assignar la regla forçosament';

// Share modal.
$string['sharetitle'] = 'Comparteix la regla {$a->title}';
$string['sharecontent'] = 'Es compartirà la regla {$a->title} amb l\'administrador, voleu continuar?';
$string['unsharetitle'] = 'Descompartir la regla {$a->title}';
$string['unsharecontent'] = 'S\'ha de descompartir la regla {$a->title} amb l\'administrador, voleu continuar?';
$string['shareaccept'] = 'Regla compartida';
$string['unshareaccept'] = 'Regla descompartida';

// Share all modal.
$string['sharealltitle'] = 'Aprobar la compartición de la regla {$a->title}';
$string['shareallcontent'] = 'Se va a aprobar la compartición de la regla {$a->title}, ¿desea continuar?';

// Capabilities.
$string['notificationsagent:createrule'] = 'Crear una regla';
$string['notificationsagent:editrule'] = 'Actualitzar una regla';
$string['notificationsagent:checkrulecontext'] = 'Comprovar el context d\'una regla';
$string['notificationsagent:deleterule'] = 'Esborrar una regla';
$string['notificationsagent:updaterulestatus'] = 'Actualitzar l\'estat d\'una regla';
$string['notificationsagent:exportrule'] = 'Exporta una regla';
$string['notificationsagent:importrule'] = 'Importa una regla';
$string['notificationsagent:assignrule'] = 'Assignar una regla';
$string['notificationsagent:forcerule'] = 'Forçar una regla';
$string['notificationsagent:updateruleshare'] = 'Actualitzar l\'estat de compartició d\'una regla';
$string['notificationsagent:shareruleall'] = 'Aprovar la compartició d\'una regla';
$string['notificationsagent:managesiterule'] = 'Gestiona les regles a nivell de lloc';
$string['notificationsagent:managecourserule'] = 'Gestiona les regles a nivell de curs';
$string['notificationsagent:manageownrule'] = 'Gestionar les regles pròpies en el curs';
$string['notificationsagent:viewassistantreport'] = 'Ver informe de regla';

$string['notificationsagent:activitycompleted'] = 'Capacitat per utilitzar la condició activitycompleted';
$string['notificationsagent:activityopen'] = 'Capacitat per utilitzar la condició activityopen';
$string['notificationsagent:coursestart'] = 'Capacitat per utilitzar la condició coursestart';
$string['notificationsagent:calendarstart'] = 'Capacitat per utilitzar la condició calendarstart';
$string['notificationsagent:sessionstart'] = 'Capacitat per utilitzar la condició sessionstart';
$string['notificationsagent:activityavailable'] = 'Capacitat necessària per utilitzar la condició d\'activitat disponible';
$string['notificationsagent:activityend'] = 'Capacitat necessària per utilitzar la condició de finalització de l\'activitat';
$string['notificationsagent:activitylastsend'] = 'Capacitat necessària per utilitzar la condició d\'últim enviament de l\'activitat';
$string['notificationsagent:activitymodified'] = 'Capacitat necessària per utilitzar la condició modificada per l\'activitat';
$string['notificationsagent:activitynewcontent'] = 'Capacitat necessària per utilitzar la condició de contingut nou de l\'activitat';
$string['notificationsagent:activitysinceend'] = 'Capacitat necessària per utilitzar l\'activitat des de la condició final';
$string['notificationsagent:activitystudentend'] = 'Capacitat necessària per utilitzar la condició final de l\'activitat de l\'estudiant';
$string['notificationsagent:calendareventto'] = 'Capacitat necessària per utilitzar l\'esdeveniment del calendari per condicionar';
$string['notificationsagent:courseend'] = 'Capacitat necessària per utilitzar la condició de finalització del curs';
$string['notificationsagent:forumnoreply'] = 'Capacitat necessària per utilitzar la condició sense resposta del fòrum';
$string['notificationsagent:numberoftimes'] = 'Capacitat necessària per utilitzar la condició de nombre de vegades';
$string['notificationsagent:sessionend'] = 'Capacitat necessària per utilitzar la condició de finalització de la sessió';
$string['notificationsagent:weekend'] = 'Capacitat necessària per utilitzar la condició del cap de setmana';


$string['notificationsagent:addusergroup'] = 'Capacitat per utilitzar l\'acció addusergroup';
$string['notificationsagent:bootstrapnotifications'] = 'Capacitat per utilitzar l\'acció bootstrapnotifications';
$string['notificationsagent:forummessage'] = 'Capacitat per utilitzar l\'acció forummessage';
$string['notificationsagent:messageagent'] = 'Capacitat per utilitzar l\'acció messageagent';
$string['notificationsagent:removeusergroup'] = 'Capacitat per utilitzar l\'acció removeusergroup';
$string['notificationsagent:usermessageagent'] = 'Capacitat per utilitzar l\'acció usermessageagent';
$string['notificationsagent:privateforummessage'] = 'Capacitat per utilitzar l\'acció privateforummessage';

// Webservices.
$string['nosuchinstance'] = 'No s\'ha trobat aquesta instància.';
$string['isnotrule'] = 'El identificador de regla no pertany a una regla.';

// Report.
$string['rulename'] = 'Nombre de la regla';
$string['report'] = 'Informe del agente de notificaciones';
$string['id'] = 'id';
$string['ruleid'] = 'Id de la regla';
$string['fullrule'] = 'Regla';
$string['userid'] = 'Id de usuario';
$string['fulluser'] = 'Usuario';
$string['fullcourse'] = 'Curs';
$string['courseid'] = 'Id del curs';
$string['actionid'] = 'Ide de la acció';
$string['fullaction'] = 'Acció';
$string['actiondetail'] = 'Detalle de la acció';
$string['timestamp'] = 'Data';

// Nav.
$string['conditions'] = 'Condicions';
$string['exceptions'] = 'Excepcions';
$string['actions'] = 'Accions';
