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

// author: Marc Burchart <marc.burchart@fernuni-hagen.de>

namespace format_ladtopics;

defined('MOODLE_INTERNAL') || die();

class blocking
{
    public static function tool_policy_accepted()
    {
        global $DB, $USER;
        $version = 3;//$_SERVER['HTTP_HOST'] == 'localhost' || '127.0.0.1' ? 1 : 3;
        $res = $DB->get_record("tool_policy_acceptances", array("policyversionid" => $version, "userid" => (int)$USER->id ), "timemodified");
        if (isset($res->timemodified) && $res->timemodified > 1000) {
            return true;
        }
        return false;
    }
}