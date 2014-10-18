
import host
import bf2.PlayerManager
import fpformat
from constants import *
from bf2 import g_debug
from bf2.stats.stats import getStatsMap, setStatsMap, getPlayerConnectionOrderIterator, setPlayerConnectionOrderIterator, roundArmies
from bf2.stats.medals import getMedalMap, setMedalMap
# ------------------------------------------------------------------------------
# omero 2006-03-31
# ------------------------------------------------------------------------------
from bf2.BF2142StatisticsConfig import http_backend_addr, http_backend_port, http_backend_asp, snapshot_prefix
from bf2.stats.miniclient import miniclient, http_postSnapshot

IGNORED_VEHICLES = [ VEHICLE_TYPE_ANTI_AIR, VEHICLE_TYPE_GDEF, VEHICLE_TYPE_PARACHUTE, VEHICLE_TYPE_SOLDIER ]
SPECIAL_WEAPONS = { WEAPON_TYPE_EU_SNIPER : WEAPON_TYPE_PAC_SNIPER, WEAPON_TYPE_EU_AR : WEAPON_TYPE_PAC_AR, WEAPON_TYPE_EU_AV : WEAPON_TYPE_PAC_AV, WEAPON_TYPE_EU_SMG : WEAPON_TYPE_PAC_SMG, WEAPON_TYPE_EU_LMG : WEAPON_TYPE_PAC_LMG, WEAPON_TYPE_EU_PISTOL : WEAPON_TYPE_PAC_PISTOL }
IGNORED_WEAPON_INDEX = NUM_WEAPON_TYPES
SPECIAL_VEHICLE = { VEHICLE_TYPE_TITAN_AA : VEHICLE_TYPE_TITAN, VEHICLE_TYPE_TITAN_GDEF : VEHICLE_TYPE_TITAN }
ARMOR_VEHICLE = [ VEHICLE_TYPE_APC, VEHICLE_TYPE_TANK ]

# Added by Chump - for bf2statistics stats
from time import time

map_start = 0

def init():
	if g_debug: print "snapshot.py[53]: Snapshot module initialized"
	host.registerGameStatusHandler(onChangeGameStatus)

def onChangeGameStatus(status):
	global map_start
	if status == bf2.GameStatus.Playing:
		map_start = time()

def invoke():

	# Added by Chump - for bf2statistics stats
	#host.pers_gamespyStatsNewGame()
	snapshot_start = host.timer_getWallTime()
	if g_debug: print "snapshot.py[64]: Gathering SNAPSHOT Data"
	snapShot = getSnapShot()
	# Print in log
	print snapShot
	# Send snapshot to Backend Server
	print "snapshot.py: Sending SNAPSHOT to backend: %s" % str(http_backend_addr)
	try:
		backend_response = http_postSnapshot( http_backend_addr, http_backend_port, http_backend_asp, snapShot )
	except Exception, e:
		print "snapshot.py: An error occurred while sending SNAPSHOT to backend: %s" % str(e)
	print "SNAPSHOT Processing Time: %d" % (host.timer_getWallTime() - snapshot_start)

## ------------------------------------------------------------------------------
## omero 2006-03-31
## ------------------------------------------------------------------------------
## always do the following at the end...
	repackStatsVectors()
#
def repackStatsVectors():
	# remove disconnected players
	cleanoutStatsVector()
	cleanoutMedalsVector()
	# repack stats and medal vector so there are no holes. gamespy doesnt like holes.
	medalMap = getMedalMap()
	statsMap = getStatsMap()
	playerOrderIt = getPlayerConnectionOrderIterator()
	newOrderIterator = 0
	newStatsMap = {}
	newMedalMap = {}
	highestId = 0
	for id, statsItem in statsMap.iteritems():
		newStatsMap[newOrderIterator] = statsItem
		if id in medalMap:
			newMedalMap[newOrderIterator] = medalMap[id]
		statsItem.connectionOrderNr = newOrderIterator
		newOrderIterator += 1
	print "snapshot.py: Repacked stats map. Stats map size=%d. OrderIt changed from %d to %d" % (len(statsMap), playerOrderIt, newOrderIterator)
	setPlayerConnectionOrderIterator(newOrderIterator)
	setStatsMap(newStatsMap)
	setMedalMap(newMedalMap)
#
def cleanoutStatsVector():
	print "snapshot.py: Cleaning out unconnected players from stats map"
	statsMap = getStatsMap()
	# remove disconnected players after snapshot was sent
	removeList = []
	for pid in statsMap:
		foundPlayer = False
		for p in bf2.playerManager.getPlayers():
			if p.stats == statsMap[pid]:
				foundPlayer = True
				break
		if not foundPlayer:
			removeList += [pid]
	for pid in removeList:
		print "snapshot.py: Removed player %d from stats." % pid
		del statsMap[pid]
#
def cleanoutMedalsVector():
	print "snapshot.py: Cleaning out unconnected players from medal map"
	medalMap = getMedalMap()
	# remove disconnected players after snapshot was sent
	removeList = []
	for pid in medalMap:
		foundPlayer = False
		for p in bf2.playerManager.getPlayers():
			if p.medals == medalMap[pid]:
				foundPlayer = True
				break
		if not foundPlayer:
			removeList += [pid]
	for pid in removeList:
		if g_debug: print "snapshot.py: Removed player %d from medals." % pid
		del medalMap[pid]
#
def getSnapShot():
	print "snapshot.py: Assembling snapshot"
	#snapShot = "\\"
	global map_start
	snapShot = snapshot_prefix + "\\" + bf2.gameLogic.getMapName() + "\\"
	snapShot += "mapstart\\" + str(map_start) + "\\mapend\\" + str(time()) + "\\"
	snapShot += "win\\" + str(bf2.gameLogic.getWinner()) + "\\"
	statsMap = getStatsMap()
	
	# ----------------------------------------------------------------------------
	# omero 2006-04-10
	# ----------------------------------------------------------------------------
	# this will be used for detecting which mod is running and
	# set standardKeys['v'] accordingly
	#
	running_mod = str(host.sgl_getModDirectory())
	if ( running_mod == "mods/bf2142" ):
		v_value = "bf2142"
	else:
		v_value = "!!!ERROR!!! fixme"

	if g_debug: print "snapshot.py: Running MOD: %s" % (str(v_value))

	standardKeys = [
			("gm",		getGameModeId(bf2.serverSettings.getGameMode())),
			("m",		getMapId(bf2.serverSettings.getMapName())),
			("v",		str(v_value)),
			("pc",		len(statsMap)),
			]
	# only send rwa key if there was a winner
	winner = bf2.gameLogic.getWinner()
	if winner != 0:
		standardKeys += [("rwa", roundArmies[winner])]
	stdKeyVals = []
	for k in standardKeys:
		stdKeyVals.append ("\\".join((k[0], str(k[1]))))
	snapShot += "\\".join(stdKeyVals)
	
	if g_debug: print "snapshot.py: Snapshot Pre-processing complete: %s" % (str(snapShot))
	
	playerSnapShots = ""
	if g_debug: print "snapshot.py: Num clients to base snap on: %d" % (len(statsMap))
	for sp in statsMap.itervalues():
		if g_debug: print "snapshot.py: Processing PID: %s" % (str(sp.profileId))
		playerSnapShot = getPlayerSnapshot(sp)
		playerSnapShots += playerSnapShot
	snapShot += playerSnapShots

	# Add EOF marker for validation
	snapShot += "\\EOF\\1"
	
	return snapShot

def getPlayerSnapshot(playerStat):
	if g_debug: print "snapshot.py: playerStat.profileId"
	awayBonus = int(playerStat.localScore.awayBonusScoreIAR + playerStat.localScore.awayBonusScore)
	totalScore = (playerStat.score - playerStat.localScore.diffRankScore) + int(playerStat.localScore.experienceScoreIAR + playerStat.localScore.experienceScore) + int(awayBonus)
	playerKeys = 	[
			("ban",		playerStat.timesBanned						),	#+ => total bans na server
			("c",		playerStat.complete							),	#? => Complete
			("capa",		playerStat.localScore.cpAssists					),	#+ => Capture Assists
			("cpt",		playerStat.localScore.cpCaptures					),	#+ => Captured CPs
			("crpt",		totalScore							),	#+ => Career Points
			("cs",		playerStat.localScore.commanderBonusScore				),	#+ => Commander Score
			("dass",		playerStat.localScore.driverAssists					),	#+ => Driver Assists
			("dcpt",		playerStat.localScore.cpDefends					),	#+ => Defended Control Points
			("dstrk",		playerStat.longestDeathStreak						),	#> => Worst Death Streak
			("dths",		playerStat.deaths							),	#+ => Deaths
			("gsco",		playerStat.score							),	#+ => Global Score
			("hls",		playerStat.localScore.heals						),	#+ => Heals
			("kick",		playerStat.timesKicked						),	#+ => total kicks from servers
			("klla",		playerStat.localScore.damageAssists					),	#+ => Kill Assists
			("klls",		playerStat.kills							),	#+ => Kills
			("klstrk",	playerStat.longestKillStreak						),	#> => Kills Streak
			("kluav",		playerStat.weapons[WEAPON_TYPE_RECON_DRONE].kills				),	#+ => Kills With Gun Drone
			("nick",		playerStat.name							),	#? => Nickname
			("ncpt",		playerStat.localScore.cpNeutralizes					),	#+ => Neutralized CPs
			("pdt",		playerStat.dogTags							),	#+ => Unique Dog Tags Collected
			("pdtc",		playerStat.dogtagCount						),	#+ => Dog Tags Collected
			("pid",		playerStat.profileId						),	#? => pID
			("resp",		playerStat.localScore.ammos						),	#+ => Re-supplies
			("rnk",		playerStat.rank							),	#> => Rank
			("rnkcg",		playerStat.roundRankup						),	#? => RankUp?
			("rps",		playerStat.localScore.repairs						),	#+ => Repairs
			("rvs",		playerStat.localScore.revives						),	#+ => Revives
			("slbspn",	playerStat.squadLeaderBeaconSpawns					),	#+ => Spawns On Squad Beacons
			("sluav",		playerStat.squadLeaderUAV						),	#+ => Spawn Dron Deployed
			("suic",		playerStat.localScore.suicides					),	#+ => Suicides
			("tac",		int(playerStat.timeAsCmd)						),	#+ => Time As Commander
			("talw",		int(playerStat.timePlayed - playerStat.timeAsCmd - playerStat.timeInSquad)	),	#+ => Time As Lone Wolf
			("tas",		playerStat.localScore.titanAttackKills					),	#+ => Titan Attack Score
			("tasl",		int(playerStat.timeAsSql)						),	#+ => Time As Squad Leader
			("tasm",		int(playerStat.timeInSquad - playerStat.timeAsSql)			),	#+ => Time As Squad Member
			("tcd",		playerStat.localScore.titanPartsDestroyed				),	#+ => Titan Components Destroyed
			("tcrd",		playerStat.localScore.titanCoreDestroyed				),	#+ => Titan Cores Destroyed
			("tdmg",		playerStat.localScore.teamDamages					),	#+ => Team Damage
			("tdrps",		playerStat.localScore.titanDrops					),	#+ => Titan Drops
			("tds",		playerStat.localScore.titanDefendKills					),	#+ => Titan Defend Score
			("tgd",		playerStat.localScore.titanWeaponsDestroyed				),	#+ => Titan Guns Destroyed
			("tgr",		playerStat.localScore.titanWeaponsRepaired				),	#+ => Titan Guns Repaired
			("tkls",		playerStat.teamkills						),	#+ => Team Kills
			("toth",		playerStat.bulletsHit						),	#+ => Total Hits
			("tots",		playerStat.bulletsFired						),	#+ => Total Fired
			("tt",		int(playerStat.timePlayed)						),	#+ => Time Played
			("tvdmg",		playerStat.localScore.teamVehicleDamages				),	#+ => Team Vehicle Damage
			("twsc",		playerStat.teamScore						),	#+ => Teamwork Score
			("t",		playerStat.team							),	# => TeamID
			
			]

	# victims / victimizers
	statsMap = getStatsMap()
	for p in playerStat.killedPlayer:
		if not p in statsMap:
			if g_debug: print "snapshot.py: killedplayer_id victim connorder: ", playerStat.killedPlayer[p], " wasnt in statsmap!"
		else:
			playerKeys.append(("mvns", str(statsMap[p].profileId)))
			playerKeys.append(("mvks", str(playerStat.killedPlayer[p])))
	keyvals = []
	for k in playerKeys:
		keyvals.append ("\\".join((k[0], str(k[1]))))
	playerSnapShot = "\\".join(keyvals)
	# medals
	medalsSnapShot = ""
	if playerStat.medals:
		medalsSnapShot = playerStat.medals.getSnapShot()
	################ vehicles
	vehiclesSS = {}
	for v in range(0, NUM_VEHICLE_TYPES):
		if g_debug: print "snapshot.py[311]: vehicle: " + str(v) + ", " + str(playerStat.vehicles[v].timeInObject)
		if v in IGNORED_VEHICLES:
			if g_debug: print "snapshot.py[313]: Ignoring vehicle " + str(v)
			continue
		vehicle = playerStat.vehicles[v]
		print str(vehicle)
		if vehicle.timeInObject > 0:
			if v in ARMOR_VEHICLE:
				keyName = "atp"
				if keyName in vehiclesSS:
					vehiclesSS["atp" + str(playerStat.playerId) ] += int(vehicle.timeInObject)
				else:
					vehiclesSS["atp" + str(playerStat.playerId) ] = int(vehicle.timeInObject)
			vehiclesSS["vdstry-" + str(v) ] = vehicle.destroyed
			vehiclesSS["vdths-"  + str(v) ] = vehicle.killedBy
			vehiclesSS["vkls-"   + str(v) ] = vehicle.kills
			vehiclesSS["vrkls-"  + str(v) ] = vehicle.roadKills
			vehiclesSS["vtp-"    + str(v) ] = int(vehicle.timeInObject)
			vehiclesSS["vbf-"    + str(v) ] = vehicle.bulletsFired
			vehiclesSS["vbh-"    + str(v) ] = vehicle.bulletsHit
			
			
	vehiclekeyvals = []
	for v in vehiclesSS:
		vehiclekeyvals.append ("\\".join((v, str(vehiclesSS[v]))))
	vehicleSnapShot = "\\".join(vehiclekeyvals)
#	# kits
	kitKeys = 	[
			("kdths-0",	playerStat.kits[KIT_TYPE_RECON].deaths					),	#+ => deads as Recon
			("kdths-1",	playerStat.kits[KIT_TYPE_ASSAULT].deaths				),	#+ => deads as Assault
			("kdths-2",	playerStat.kits[KIT_TYPE_ANTI_VEHICLE].deaths				),	#+ => deads as Engineer
			("kdths-3",	playerStat.kits[KIT_TYPE_SUPPORT].deaths				),	#+ => deads as Support
			("kkls-0",	playerStat.kits[KIT_TYPE_RECON].kills					),	#+ => Kills As Recon
			("kkls-1",	playerStat.kits[KIT_TYPE_ASSAULT].kills					),	#+ => Kills As Assault
			("kkls-2",	playerStat.kits[KIT_TYPE_ANTI_VEHICLE].kills				),	#+ => Kills As Engineer
			("kkls-3",	playerStat.kits[KIT_TYPE_SUPPORT].kills					),	#+ => Kills As Support
			("ktt-0",		int(playerStat.kits[KIT_TYPE_RECON].timeInObject)				),	#+ => Time As Recon
			("ktt-1",		int(playerStat.kits[KIT_TYPE_ASSAULT].timeInObject)			),	#+ => Time As Assault
			("ktt-2",		int(playerStat.kits[KIT_TYPE_ANTI_VEHICLE].timeInObject)			),	#+ => Time As Engineer
			("ktt-3",		int(playerStat.kits[KIT_TYPE_SUPPORT].timeInObject)			),	#+ => Time As Support
			]
	kitkeyvals = []
	for k in kitKeys:
		kitkeyvals.append ("\\".join((k[0], str(k[1]))))
	kitSnapShot = "\\".join(kitkeyvals)
	############## weapons
	weaponsSS = {}
	for w in range(0, NUM_WEAPON_TYPES):
		if g_debug: print "snapshot.py[86]: weapon: " + str(w) + ", " + str(playerStat.weapons[w].timeInObject)

		weapon = playerStat.weapons[w]
		if weapon.timeInObject > 0:
			weaponsSS["wdths-" + str(w) ] = weapon.killedBy
			weaponsSS["wkls-"  + str(w) ] = weapon.kills
			weaponsSS["waccu-" + str(w) ] = "%.3g" % weapon.accuracy
			weaponsSS["wtp-"   + str(w) ] = int(weapon.timeInObject)
			weaponsSS["wbf-"   + str(w) ] = weapon.bulletsFired
			weaponsSS["wbh-"   + str(w) ] = weapon.bulletsHit
	weaponkeyvals = []
	for w in weaponsSS:
		weaponkeyvals.append ("\\".join((w, str(weaponsSS[w]))))
	weaponSnapShot = "\\".join(weaponkeyvals)
	allSnapShots = []
	if len(playerSnapShot) > 0 : allSnapShots = allSnapShots + [playerSnapShot]
	if len(medalsSnapShot) > 0 : allSnapShots = allSnapShots + [medalsSnapShot]
	if len(vehicleSnapShot) > 0 : allSnapShots = allSnapShots + [vehicleSnapShot]
	if len(kitSnapShot) > 0 : allSnapShots = allSnapShots + [kitSnapShot]
	if len(weaponSnapShot) > 0 : allSnapShots = allSnapShots + [weaponSnapShot]
	playerSnapShot = "\\".join(allSnapShots)
	if g_debug: print "\n-------\n" + str(playerSnapShot) + "\n-------\n"
	# add pid to all keys (gamespy likes this)
	transformedSnapShot = ""
	i = 0
	idString = "_" + str(playerStat.connectionOrderNr)
	while i < len(playerSnapShot):
		key = ""
		while playerSnapShot[i] != "\\":
			key += playerSnapShot[i]
			i += 1
		i += 1
		value = ""
		while i < len(playerSnapShot) and playerSnapShot[i] != "\\":
			value += playerSnapShot[i]
			i += 1
		transformedKeyVal = key + idString + "\\" + value
		if i != len(playerSnapShot):
			transformedKeyVal += "\\"
		transformedSnapShot += transformedKeyVal
		i += 1
	return "\\" + transformedSnapShot
