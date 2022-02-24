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

if(!defined('MEDIAWIKI'))
    die();

$wgExtensionCredits[ 'other' ][] = array(
    'path' => __FILE__,
    'name' => 'EnhancedReportTime',
    'author' => 'Davis Mosenkovs',
    'url' => 'https://www.mediawiki.org/wiki/Extension:EnhancedReportTime',
    'description' => 'Displays enhanced information about generation time of wiki pages',
    'version' => '1.1.0',
);

$wgExtensionMessagesFiles['EnhancedReportTime'] = dirname( __FILE__ ) . '/EnhancedReportTime.i18n.php';

/*** Default configuration ***/
// Use request start timestamp from $_SERVER['REQUEST_TIME_FLOAT'] (when available).
$wgERTUseServerStartTime = true;

// Maximum allowed generation time for which to report that SLA is met.
$wgERTSLATime = 10;

// Name of PHP function with additional tests. This function must return true on success or string with error message on failure.
$wgERTTestFunction = '';

// Array with page names (see magic word {{FULLPAGENAME}}) where to enable EnhancedReportTime (empty means everywhere).
$wgERTPages = array('Special:Version');
/*****************************/

$wgHooks['AfterFinalPageOutput'][] = 'wfEnhancedReportTimeAfterFinalPageOutput';

function wfEnhancedReportTimeAfterFinalPageOutput($output) {
    global $wgERTPages;
    if(count($wgERTPages)==0 || in_array($output->getTitle()->getPrefixedText(), $wgERTPages, true)) {
        echo wfEnhancedReportTimeReport();
    }
    return true;
}

function wfEnhancedReportTimeReport() {
    global $wgERTUseServerStartTime, $wgERTSLATime, $wgERTTestFunction, $wgRequestTime, $wgShowHostnames;

    $starttime = $wgRequestTime;
    $stserver = false;
    $testresult = true;
    $slamessage = '';
    
    if($wgERTUseServerStartTime && isset($_SERVER['REQUEST_TIME_FLOAT'])) {
        $starttime = $_SERVER['REQUEST_TIME_FLOAT'];
        $stserver = true;
    }
    
    if($wgERTTestFunction != '') {
        $testresult = $wgERTTestFunction();
    }
    
    $elapsed = microtime(true) - $starttime;
    if($wgERTSLATime > 0) {
        $slamessage = ' '.wfMessage($elapsed <= $wgERTSLATime ? 'enhancedreporttime-sla-met' : 'enhancedreporttime-sla-notmet', $wgERTSLATime)->text();
    }

    if($wgShowHostnames) {
        return wfMessage('enhancedreporttime-text-host', round($elapsed, 3), $stserver ? 'REQUEST_TIME_FLOAT' : '$wgRequestTime', $testresult===true ? $slamessage : ' '.$testresult, wfHostname())->text();
    } else {
        return wfMessage('enhancedreporttime-text-nohost', round($elapsed, 3), $stserver ? 'REQUEST_TIME_FLOAT' : '$wgRequestTime', $testresult===true ? $slamessage : ' '.$testresult)->text();
    }
}
