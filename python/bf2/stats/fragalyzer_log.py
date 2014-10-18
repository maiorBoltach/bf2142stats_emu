# fragalyzer log file generator. 
# 
# enable by typing this in the console:
# pythonHost.sendCommand falog 1

# needs to be re-enabled in each round.

import host
import bf2.PlayerManager
import bf2.GameLogic
import fpformat
import datetime
import time
import fpformat
from constants import *
from bf2 import g_debug

logfile = None
fileName = ""


def init():
	host.registerHandler('ConsoleSendCommand', onSendCommand)

	if g_debug: print "Fragalyzer log module initialized."

	
def enable():
	global fileName
	global logfile
	global startTime

	if logfile and not logfile.closed:
		print "Fragalyzer logging already enabled"
		return 
		
	host.registerGameStatusHandler(onGameStatusChanged)
	
	currentDate = datetime.datetime.today()
	dateString = ""
	dateString = time.strftime("%y%m%d_%H%M", currentDate.timetuple())

	if dateString != "":
		fileName = bf2.gameLogic.getModDir() + "/Logs/" + bf2.gameLogic.getMapName() + "_" + dateString + "_faLog.txt"
	else:
		fileName = bf2.gameLogic.getModDir() + "/Logs/" + bf2.gameLogic.getMapName() + "_faLog.txt"
		
	fileName = fileName.replace('/', '\\')
	
	print "log file: ", fileName

	try:
		logfile = file (fileName, 'w')
	except Exception:
		if g_debug: print "Couldnt open fragalyzer logfile: ", fileName
		return
	
	startTime = int(date())
	timeString = str(startTime)
	startDate = time.strftime("%Y.%m.%d,%H:%M", currentDate.timetuple())
	
	logfile.write("Frag Analyzer Log\n")
	logfile.write("LEVELNAME " + bf2.gameLogic.getMapName() + "\n")		
	logfile.flush()


	# register events
	host.registerHandler('PlayerKilled', onPlayerKilled)
	host.registerHandler('PlayerDeath', onPlayerDeath)
	host.registerHandler('EnterVehicle', onEnterVehicle)
	host.registerHandler('ExitVehicle', onExitVehicle)
	host.registerHandler('PickupKit', onPickupKit)
	host.registerHandler('DropKit', onDropKit)
	host.registerHandler('ControlPointChangedOwner', onCPStatusChange)
	host.registerHandler('PlayerScore', onPlayerScore)
	host.registerHandler('PlayerSpawn', onPlayerSpawn)

	host.registerHandler('PlayerConnect', onPlayerConnect, 1)
	
	host.registerHandler('PlayerPosition', onPlayerPosition)

	# Connect already connected players if reinitializing
	for p in bf2.playerManager.getPlayers():
		onPlayerConnect(p)
	
	print "Fragalyzer logging enabled."


class faStat:
	def __init__(self):
		self.enterAt = 0
		self.enterTemplate = None
		self.spawnAt = 0
		
	def copyStats(self, player):
		self.damageAssists = player.score.damageAssists
		self.passengerAssists = player.score.passengerAssists
		self.targetAssists = player.score.targetAssists
		self.revives = player.score.revives
		self.teamDamages = player.score.teamDamages
		self.teamVehicleDamages = player.score.teamVehicleDamages
		self.cpCaptures = player.score.cpCaptures
		self.cpDefends = player.score.cpDefends
		self.cpAssists = player.score.cpAssists
		self.cpNeutralizes = player.score.cpNeutralizes
		self.cpNeutralizeAssists = player.score.cpNeutralizeAssists
		self.suicides = player.score.suicides
		self.kills = player.score.kills
		self.TKs = player.score.TKs

	def getChangedStats(self, player):
		res = []
		if player.score.cpCaptures > self.cpCaptures:
			res += ["cpCaptures"]
		if player.score.cpDefends > self.cpDefends:
			res += ["cpDefends"]
		if player.score.cpAssists > self.cpAssists:
			res += ["cpAssists"]
		if player.score.cpNeutralizes > self.cpNeutralizes:
			res += ["cpNeutralizes"]
		if player.score.cpNeutralizeAssists > self.cpNeutralizeAssists:
			res += ["cpNeutralizeAssists"]
		if player.score.suicides > self.suicides:
			res += ["suicides"]
		if player.score.kills > self.kills:
			res += ["kills"]
		if player.score.TKs > self.TKs:
			res += ["TKs"]
		if player.score.damageAssists > self.damageAssists:
			res += ["damageAssists"]
		if player.score.passengerAssists > self.passengerAssists:
			res += ["passengerAssists"]
		if player.score.targetAssists > self.targetAssists:
			res += ["targetAssists"]
		if player.score.revives > self.revives:
			res += ["revives"]
		if player.score.teamDamages > self.teamDamages:
			res += ["teamDamages"]
		if player.score.teamVehicleDamages > self.teamVehicleDamages:
			res += ["teamVehicleDamages"]
		
			
		return res
		

def onPlayerConnect(player):
	player.fa = faStat()	
	player.fa.enterAt = date()
	if player.isAlive():
		player.fa.spawnAt = date()
	vehicle = player.getVehicle()
	onEnterVehicle(player, vehicle)
	kit = player.getKit()
	if kit:
		onPickupKit(player, kit)
		
	player.fa.copyStats(player)

	
def disable():
	if logfile:
		timeString = str(int(date()))
		logfile.write("DISABLE LevelName=" + bf2.gameLogic.getMapName() + " EndTime=" + timeString + "\n")
		logfile.close()
		print "Fragalyzer logging disabled."
	else:
		print "Fragalyzer logging was already disabled."



def onGameStatusChanged(status):
	if status == bf2.GameStatus.Playing:
		pass

	elif status == bf2.GameStatus.EndGame:
		onEor()
		disable()


def onEor():
	if logfile and not logfile.closed:
		team1tickets = bf2.gameLogic.getTickets(1)
		team2tickets = bf2.gameLogic.getTickets(2)
	
		#team1 = PAC, team2 = EU
		logfile.write("EOR " + str(team1tickets) + " " + str(team2tickets) + "\n")
		logfile.flush()


def getPosStr(orgPos):
	worldSize = bf2.gameLogic.getWorldSize();
	scale = [512.0 / worldSize[0], 1, 512.0 / worldSize[1]]
	pos = [orgPos[0] * scale[0], orgPos[1] * scale[1], orgPos[2] * scale[2]]
	res = str(fpformat.fix(pos[0], 3)) + " " + str(fpformat.fix(pos[1], 3)) + " " + str(fpformat.fix(pos[2], 3))
	return res


def onSendCommand(command, args):
	if string.lower(command) == "falog":
		if len(args) > 0:
			if args[0] == "1":
				enable()
			elif args[0] == "0":
				disable()
		

def date():
	return host.timer_getWallTime()

def wallString():
	return str(int(host.timer_getWallTime()) - startTime)

def onEnterVehicle(player, vehicle, freeSoldier = False):
	if player == None: return
	rootVehicle = bf2.objectManager.getRootParent(vehicle)
	if rootVehicle.templateName == 'MultiPlayerFreeCamera':
		return
		
	vehicleType = getVehicleType(rootVehicle.templateName)

	if vehicleType == VEHICLE_TYPE_SOLDIER:
		pass
	else:
		timeString = wallString()
		playerTeam = str(player.getTeam())
		logfile.write("ENTER PlayerName=" + player.getName() + " PlayerTeam=" + playerTeam + " VehicleName=" + rootVehicle.templateName + " Time=" + timeString + "\n")
		player.fa.enterAt = date()
		player.fa.enterTemplate = rootVehicle.templateName
		

	logfile.flush()

	return

def onPlayerSpawn(player, soldier):
	pass

def onExitVehicle(player, vehicle):
	if player == None: return
	rootVehicle = bf2.objectManager.getRootParent(vehicle)
	vehicleType = getVehicleType(rootVehicle.templateName)
	playerTeam = str(player.getTeam())

	if vehicleType == VEHICLE_TYPE_SOLDIER:
		pass
	else:
		timeInVehicle = 0
		if player.fa.enterTemplate == rootVehicle.templateName:
			timeInVehicle = date() - player.fa.enterAt
		timeString = wallString()
		logfile.write("EXIT " + player.getName() + " " + rootVehicle.templateName + " " + timeString + "\n")
		
	player.fa.enterAt = 0
	
	logfile.flush()
	return

def onPickupKit(player, kit):
	timeString = wallString()
	playerSpawnTimePickupDiff = str(int(date())-int(player.stats.spawnedAt))
	playerTeam = str(player.getTeam())
	logfile.write("PICKUPKIT PlayerName=" + player.getName() + " PlayerTeam=" + playerTeam + " PlayerKit=" + kit.templateName + " PickupSpawnDiff=" + playerSpawnTimePickupDiff + " Time=" + timeString + "\n")
	player.fa.spawnAt = date()
	player.lastKitTemplateName = kit.templateName
	logfile.flush()
	
def onDropKit(player, kit):
	timeInVehicle = 0
	if player.fa.spawnAt != 0:
		timeInVehicle = date() - player.fa.spawnAt 
	timeString = wallString()
	playerTeam = str(player.getTeam())
	logfile.write("DROPKIT " + player.getName() + " " + kit.templateName + " " + timeString + "\n")
	logfile.flush()
	return

def onPlayerKilled(victim, attacker, weapon, assists, object):
	victimKitName = victim.lastKitTemplateName
	victimVehicle = victim.getVehicle()
	victimRootVehicle = bf2.objectManager.getRootParent(victimVehicle)
	victimVehicleType = getVehicleType(victimRootVehicle.templateName)
	victimName = victim.getName()
	victimTeam = str(victim.getTeam())

	if attacker:
		attackerKitName = attacker.lastKitTemplateName	
		attackerVehicle = attacker.getVehicle()
		attackerRootVehicle = bf2.objectManager.getRootParent(attackerVehicle)
		attackerVehicleType = getVehicleType(attackerRootVehicle.templateName)
		attackerName = attacker.getName()
		attackerTeam = str(attacker.getTeam())
	else:
		attackerKitName = "unknown"
		attackerVehicle = None
		attackerRootVehicle = None
		attackerVehicleType = VEHICLE_TYPE_UNKNOWN
		attackerTeam = ""
		attackerName = "suicide"

	if attacker:
		attackPos = getPosStr(attackerVehicle.getPosition())
	else:
		attackPos =getPosStr(victimVehicle.getPosition())
		
	if victimVehicle != None:
		victimPos = getPosStr(victimVehicle.getPosition())
		
	if weapon != None:
		deathType = "killed"
	else:
		deathType = "gibbed"
	
	weaponName = "unknown"
	if weapon != None:
		weaponName = weapon.templateName
	if attackerVehicle != None:
		weaponName = attackerVehicle.templateName
		
	print "victimName: ", victimName
	print "deathType: ", deathType
	print "victimKitName: ", victimKitName
	print "victimKitName: ", victimPos
	print "victimTeam: ", victimTeam
	print "attackerName: ", attackerName
	print "attackerKitName: ", attackerKitName
	print "weaponName: ", weaponName
	print "attackerTeam: ", attackerTeam
	print "attackPos: ", attackPos
	logfile.write("KILL " + victimName + " " + deathType + " " + victimKitName + " " + victimPos + " " + victimTeam + " " + attackerName + " " + attackerKitName + " " + weaponName + " " + attackerTeam + " " + attackPos + "\n")
	logfile.flush()

def onPlayerDeath(victim, vehicle):

	# dump accuracy stats on death (can't invoke on each shot being fired)
	tempFireMap = {}
	bulletsHit = victim.score.bulletsGivingDamage
	#logfile.write("Bullets hit: " + bulletsHit)
	
	for b in bulletsHit:
		templateName = b[0]
		nr = b[1]
		tempFireMap[templateName] = nr

	bulletsFired = victim.score.bulletsFired
	#print "bf: ", len(bulletsFired)
	
	if g_debug: print "bf: ", len(bulletsFired)
	for b in bulletsFired:
		templateName = b[0]
		fired = b[1]
		hits = 0
		if templateName in tempFireMap:
			hits = tempFireMap[templateName]
		timeString = wallString()
		#sscanf(line.c_str(), "%s %s %s %i %i", tag, playerName, weaponName, &fired, &hit);
		logfile.write("FIRED " + victim.getName() + " " + templateName + " " + str(fired) + " " + str(hits) + "\n")

	logfile.flush()


def onCPStatusChange(cp, attackingTeam):
	position = cp.getPosition()
	
	print "cp.cp_getParam('team'): ", cp.cp_getParam('team')
	print "attackingTeam: ", attackingTeam
	
	if (cp.cp_getParam('team') != 0):
		captureType = "team"
	else:
#		if attackingTeam == 0:
#			return
		captureType = "neutral"
	
	timeString = wallString()

	#sscanf(line.c_str(), "%s %i %s %i %f %f %f", tag, &number, capType, &team, &x, &y, &z);	
	logfile.write("CAPTURE " + cp.getTemplateProperty('controlPointId') + " " + captureType + " " + str(cp.cp_getParam('team')) + " " + getPosStr(cp.getPosition()) + "\n")
	 
	logfile.flush()
	
	 
def onPlayerScore(player, difference):
	if logfile and not logfile.closed:
		print "fragalyzer_log.py[384]: " + str(player)+ " " + " " + str(difference)
		if player != None:
			playerKitName = player.lastKitTemplateName
			playerVeh = player.getVehicle()
			playerRootVeh = bf2.objectManager.getRootParent(playerVeh)
			playerVehName = playerRootVeh.templateName
			playerVehType = getVehicleType(playerRootVeh.templateName)
			timeString = wallString()
			
			# figure out score type
			scoreTypeList = player.fa.getChangedStats(player)
			player.fa.copyStats(player)
			if len(scoreTypeList):
				scoreType = scoreTypeList[0]
			else:
				scoreType = "Unknown"
				
			logfile.write("SCORE ScoreDiff=" + str(difference) + " PlayerName=" + player.getName() + " PlayerTeam=" + str(player.getTeam()) + " PlayerKit=" + playerKitName)
#			if (playerVeh != None) and (playerVehType != VEHICLE_TYPE_SOLDIER):
#				logfile.write(" PlayerVehicle=" + playerVehName)
#			logfile.write(" PlayerPos=" + getPosStr(playerVeh.getPosition()) + " Time=" + timeString + " Scoretype=" + scoreType + "\n")
			logfile.flush()

def onPlayerPosition(player, inVehicle):
	if player != None:
		playerPos = getPosStr(player.getDefaultVehicle().getPosition())
		playerTeam = str(player.getTeam())
		
		logfile.write("POS " + playerTeam + " " + playerPos + " " + str(inVehicle) + "\n")
		logfile.flush()
