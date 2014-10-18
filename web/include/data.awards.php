<?php

/*
	Copyright (C) 2006  BF2142Statistics

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*******************************************
* 14/08/06 v0.0.1 - Initial build           *
*******************************************/

// Where clause Substitution String
$awards_substr = "###";

function buildAwardsData($mod) {

	$awardsdata = array();
	
	// Data: array(<short name>, <0 = badges, 1 = Other>)
	
	#Badges
	$awardsdata["ssb"]	= array(100, 0);
	$awardsdata["rsb"]	= array(101, 0);
	$awardsdata["asb"]	= array(102, 0);
	$awardsdata["avsb"]	= array(103, 0);
	$awardsdata["slsb"]	= array(104, 0);
	$awardsdata["cb"]	= array(105, 0);
	$awardsdata["pcb"]	= array(106, 0);
	$awardsdata["egb"]	= array(107, 0);
	$awardsdata["adb"]	= array(108, 0);
	$awardsdata["ceb"]	= array(109, 0);
	$awardsdata["tcb"]	= array(110, 0);
	$awardsdata["eeb"]	= array(111, 0);
	$awardsdata["meb"]	= array(112, 0);
	$awardsdata["resb"]	= array(113, 0);
	$awardsdata["arsb"]	= array(114, 0);
	$awardsdata["hsb"]	= array(115, 0);
	$awardsdata["tsb"]	= array(116, 0);
	$awardsdata["tceb"]	= array(117, 0);
	$awardsdata["tdeb"]	= array(118, 0);
	$awardsdata["tdab"]	= array(119, 0);
	$awardsdata["acb"]	= array(120, 0);
	$awardsdata["veb"]	= array(121, 0);
	
	#Ribbons
	$awardsdata["Adr"]		= array(300, 0);
	$awardsdata["Hsr"]		= array(301, 0);
	$awardsdata["Hr"]		= array(302, 0);
	$awardsdata["Ior"]		= array(303, 0);
	$awardsdata["Ccr"]		= array(304, 0);
	$awardsdata["Dusr"]		= array(305, 0);
	$awardsdata["Musr"]		= array(306, 0);
	$awardsdata["Vusr"]		= array(307, 0);
	$awardsdata["Wcr"]		= array(308, 0);
	$awardsdata["Asr"]		= array(309, 0);
	$awardsdata["Csr"]		= array(310, 0);
	$awardsdata["Pdr"]		= array(311, 0);
	$awardsdata["Edr"]		= array(312, 0);
	$awardsdata["Smr"]		= array(313, 0);
	$awardsdata["Gcr"]		= array(314, 0);
	$awardsdata["Lomr"]		= array(315, 0);
	$awardsdata["Gbdr"]		= array(316, 0);
	$awardsdata["Aesr"]		= array(317, 0);
	$awardsdata["Tadr"]		= array(318, 0);
	$awardsdata["Tcr"]		= array(319, 0);
	$awardsdata["Osr"]		= array(320, 0);
	$awardsdata["Cfusr"]	        = array(321, 0);
	$awardsdata["Tdr"]		= array(322, 0);
	$awardsdata["Mwr"]		= array(323, 0);
	
	#medals
	$awardsdata["erb"]	= array(200, 1);
	$awardsdata["ers"]	= array(201, 1);
	$awardsdata["erg"]	= array(202, 1);
	$awardsdata["Dsm"]	= array(203, 0);
	$awardsdata["Icm"]	= array(204, 0);
	$awardsdata["Micb"]	= array(205, 0);
	$awardsdata["Icmm"]	= array(206, 0);
	$awardsdata["Mog"]	= array(207, 0);
	$awardsdata["Ehc"]	= array(208, 0);
	$awardsdata["Dpa"]	= array(209, 0);
	$awardsdata["Mcm"]	= array(210, 0);
	$awardsdata["Mtm"]	= array(211, 0);
	$awardsdata["Hcm"]	= array(212, 0);
	$awardsdata["Asm"]	= array(213, 0);
	$awardsdata["Gcm"]	= array(214, 0);
	$awardsdata["Hsm"]	= array(215, 0);
	$awardsdata["Ph"]	= array(216, 1);
	$awardsdata["Attm"]	= array(217, 0);
	$awardsdata["Tme"]	= array(218, 0);
	$awardsdata["Gbm"]	= array(219, 0);
	
	#pins
	$awardsdata["Cep"]	= array(400, 1);
	$awardsdata["Dcep"]	= array(401, 1);
	$awardsdata["Psp"]	= array(402, 1);
	$awardsdata["Tdsp"]	= array(403, 1);
	$awardsdata["Ttp"]	= array(404, 1);
	$awardsdata["Wgp"]	= array(405, 1);
	$awardsdata["Tdep"]	= array(406, 1);
	$awardsdata["Ip"]	= array(407, 1);
	$awardsdata["Wohp"]	= array(408, 1);
	$awardsdata["Cp"]	= array(409, 1);
	$awardsdata["Eep"]	= array(410, 1);
	$awardsdata["Erp"]	= array(411, 1);
	$awardsdata["Tsp"]	= array(412, 1);
	$awardsdata["Fep"]	= array(413, 1);
	$awardsdata["Csp"]	= array(414, 1);
	$awardsdata["Ccp"]	= array(415, 1);
	$awardsdata["Alap"]	= array(416, 1);

	return $awardsdata;
}

?>