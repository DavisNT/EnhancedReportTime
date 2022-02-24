<?php
/* EnhancedReportTime - MediaWiki extension to display enhanced information
 * about generation time of wiki pages.
 * Copyright (C) 2013-2022 Davis Mosenkovs
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

$messages = array();
$messages['en'] = array(
    'enhancedreporttime-text-host' => '<!-- Served by $4 in $1 secs. Start from $2.$3 -->',
    'enhancedreporttime-text-nohost' => '<!-- Served in $1 secs. Start from $2.$3 -->',
    'enhancedreporttime-sla-met' => 'SLA of $1 secs met.',
    'enhancedreporttime-sla-notmet' => 'SLA of $1 secs NOT met.',
);
