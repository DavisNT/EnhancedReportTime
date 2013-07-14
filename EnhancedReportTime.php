<?php
/* EnhancedReportTime - MediaWiki extension to display enhanced information
 * about generation time of wiki pages.
 * Copyright (C) 2013 Davis Mosenkovs
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
    'version' => '1.0.0',
);

$wgExtensionMessagesFiles['EnhancedReportTime'] = dirname( __FILE__ ) . '/EnhancedReportTime.i18n.php';

$wgERTUseServerStartTime = true;
$wgERTSLATime = 10;
$wgERTPages = array('Special:Version');

$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'wfEnhancedReportTimeOutputPageBeforeExec';
function wfEnhancedReportTimeOutputPageBeforeExec($sk, &$tpl) {
    global $wgERTPages;
    if(count($wgERTPages)==0 || in_array($sk->getTitle()->getPrefixedText(), $wgERTPages, true)) {
        $tpl->set('reporttime', wfEnhancedReportTimeReport());
    }
    return true;
}

function wfEnhancedReportTimeReport() {
    global $wgERTUseServerStartTime, $wgERTSLATime, $wgRequestTime, $wgShowHostnames;

    $starttime = $wgRequestTime;
    $stserver = false;
    $slamessage = '';
    
    if($wgERTUseServerStartTime && isset($_SERVER['REQUEST_TIME_FLOAT'])) {
        $starttime = $_SERVER['REQUEST_TIME_FLOAT'];
        $stserver = true;
    }
    
    $elapsed = microtime(true) - $starttime;
    if($wgERTSLATime > 0) {
        $slamessage = ' '.wfMessage($elapsed <= $wgERTSLATime ? 'enhancedreporttime-sla-met' : 'enhancedreporttime-sla-notmet', $wgERTSLATime)->text();
    }

    if($wgShowHostnames) {
        return wfMessage('enhancedreporttime-text-host', round($elapsed, 3), $stserver ? 'REQUEST_TIME_FLOAT' : '$wgRequestTime', $slamessage, wfHostname())->text();
    } else {
        return wfMessage('enhancedreporttime-text-nohost', round($elapsed, 3), $stserver ? 'REQUEST_TIME_FLOAT' : '$wgRequestTime', $slamessage)->text();
    }
}
