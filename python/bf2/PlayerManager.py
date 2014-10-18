
import host
from bf2 import g_debug

# ingame scoremanager link
ingameScores = ('deaths','kills','TKs','score','skillScore','rplScore','cmdScore','fracScore','rank',
		'bulletsFired','bulletsGivingDamage','bulletsFiredAndClear','bulletsGivingDamageAndClear')

class PlayerScore:
	def __init__(self, index):
		self.__dict__['index'] = index
		self.reset()

	def reset(self):
		# these scores are only tracked in script
		self.__dict__['heals'] = 0
		self.__dict__['ammos'] = 0
		self.__dict__['repairs'] = 0
		self.__dict__['damageAssists'] = 0
		self.__dict__['passengerAssists'] = 0
		self.__dict__['driverAssists'] = 0
		self.__dict__['targetAssists'] = 0
		self.__dict__['driverSpecials'] = 0
		self.__dict__['revives'] = 0
		self.__dict__['teamDamages'] = 0
		self.__dict__['teamVehicleDamages'] = 0
		self.__dict__['cpCaptures'] = 0
		self.__dict__['cpDefends'] = 0
		self.__dict__['cpAssists'] = 0
		self.__dict__['suicides'] = 0
		self.__dict__['cpNeutralizes'] = 0
		self.__dict__['cpNeutralizeAssists'] = 0
		self.__dict__['titanDamage'] = 0
		self.__dict__['titanPartsDestroyed'] = 0
		self.__dict__['titanPartsRepaired'] = 0
		self.__dict__['titanWeaponsDestroyed'] = 0
		self.__dict__['titanWeaponsRepaired'] = 0
		self.__dict__['titanDefendKills'] = 0
		self.__dict__['titanAttackKills'] = 0
		self.__dict__['titanCoreDestroyed'] = 0
		self.__dict__['titanCoreRepaired'] = 0
		self.__dict__['titanDrops'] = 0
		self.__dict__['titanAirDrops'] = 0
		self.__dict__['disabledVehicles'] = 0
		self.__dict__['groundCannonsDestroyed'] = 0
		self.__dict__['groundCannonsRepaired'] = 0
		self.__dict__['missilesDestroyed'] = 0
		self.__dict__['squadMemberBonusScore'] = 0.0
		self.__dict__['squadLeaderBonusScore'] = 0.0
		self.__dict__['commanderBonusScore'] = 0.0	
		self.__dict__['diffRankScore'] = 0
		self.__dict__['experienceScore'] = 0
		self.__dict__['experienceScoreIAR'] = 0
		self.__dict__['awayBonusScore'] = 0.0
		self.__dict__['awayBonusScoreIAR'] = 0.0
		self.__dict__['cmdTitanScore'] = 0.0	
		self.__dict__['cmdPyScore'] = 0.0
		self.__dict__['rplPyScore'] = 0.0
		self.__dict__['fullCaptures'] = 0
		


	def __getattr__(self, name):
		if name in self.__dict__: return self.__dict__[name]
		elif name == 'dkRatio':
			kills = host.pmgr_getScore(self.index, 'kills')
			if kills == 0:
				# div by zero is undefined -> 0:0 = 1 1:0 = 2 1:1 = 1
				return 1.0 * host.pmgr_getScore(self.index, 'deaths') + 1 
			else:
				return 1.0 * host.pmgr_getScore(self.index, 'deaths') / kills
		elif name in ingameScores: 
			return host.pmgr_getScore(self.index, name)
		else:
			raise AttributeError, name

	def __setattr__(self, name, value):
		if name in self.__dict__: 
			self.__dict__[name] = value 
			return None
		elif name in ingameScores: 
			return host.pmgr_setScore(self.index, name, value)
		else:
			raise AttributeError, name

		

class Player:
	def __init__(self, index):
		self.index = index
		self.score = PlayerScore(index)
		
	def isValid(self): return host.pmgr_isIndexValid(self.index)
	def isRemote(self): return host.pmgr_p_get("remote", self.index)
	def isAIPlayer(self): return host.pmgr_p_get("ai", self.index)
	def isAutoController(self): return host.pmgr_p_get("autoController", self.index)
	def isAlive(self): return host.pmgr_p_get("alive", self.index)
	def isManDown(self): return host.pmgr_p_get("mandown", self.index)
	def isConnected(self): return host.pmgr_p_get("connected", self.index)
	def getProfileId(self): return host.pmgr_p_get("profileid", self.index)

	def isFlagHolder(self): return host.pmgr_p_get("fholder", self.index)

	def getTeam(self): return host.pmgr_p_get("team", self.index)
	def setTeam(self, t): return host.pmgr_p_set("team", self.index, t)
	def getPing(self): return host.pmgr_p_get("ping", self.index)

	def getSuicide(self): return host.pmgr_p_get("suicide", self.index)
	def setSuicide(self, t): return host.pmgr_p_set("suicide", self.index, t)
	
	def getTimeToSpawn(self): return host.pmgr_p_get("tts", self.index)
	def setTimeToSpawn(self, t): return host.pmgr_p_set("tts", self.index, t)

	def getSquadId(self): return host.pmgr_p_get("sqid", self.index)
	def isSquadLeader(self): return host.pmgr_p_get("isql", self.index)
	def isCommander(self): return host.pmgr_p_get("commander", self.index)

	def getName(self): return host.pmgr_p_get("name", self.index)
	def setName(self, name): return host.pmgr_p_set("name", self.index, name)

	def getSpawnGroup(self): return host.pmgr_p_get("sgr", self.index)
	def setSpawnGroup(self, t): return host.pmgr_p_set("sgr", self.index, t)
	
	def getKit(self): return host.pmgr_p_get("kit", self.index)
	def getVehicle(self): return host.pmgr_p_get("vehicle", self.index)
	def getDefaultVehicle(self): return host.pmgr_p_get("defaultvehicle", self.index)
	def getPrimaryWeapon(self): return host.pmgr_p_get("weapon", self.index, 0)
	def getAllWeapons(self): return host.pmgr_p_get("allweapons", self.index, 0)

	def getAddress(self): return host.pmgr_p_get("addr", self.index)
	
	def setIsInsideCP(self, val): return host.pmgr_p_set("isInsideCP", self.index, val)
	def getIsInsideCP(self): return host.pmgr_p_get("isInsideCP", self.index)

	def getOrderPosition(self): return host.pmgr_p_get("orderPosition", self.index)
	def getOrderRadius(self): return host.pmgr_p_get("orderRadius", self.index)
	def getIfAcceptedOrder(self): return host.pmgr_p_get("ifAcceptedOrder", self.index)
	def getIfAcceptedAttackDefendOrder(self): return host.pmgr_p_get("ifAcceptedAttackDefendOrder", self.index)
	def setOrderPoint(self, t): return host.pmgr_p_set("orderPoint", self.index, t)
	def setMaxOrderPoint(self, t): return host.pmgr_p_set("maxOrderPoint", self.index, t)

	def getTotalDogtagCount(self): return host.pmgr_p_get("totalDogtagCount", self.index)
	def setTotalDogtagCount(self, t): return host.pmgr_p_set("totalDogtagCount", self.index, t)
	
	def getCDKeyHash(self): return host.pmgr_p_get("cdKeyHash", self.index)
	
class PlayerManager:
	def __init__(self):
		print "PlayerManager created"
		self._pcache = {}
		
	def getNumberOfPlayers(self):
		return host.pmgr_getNumberOfPlayers()
	
	def getCommander(self, team):
		return self.getPlayerByIndex(host.pmgr_getCommander(team))

	def getSquadLeader(self, team, squadId): 
		return self.getPlayerByIndex(host.pmgr_getSquadLeader(team, squadId))

	def getPlayers(self):
		indices = host.pmgr_getPlayers()
		players = []
		# NOTE: this uses getPlayerByIndex so we return cached player objects
		# whenever we can
		for i in indices: players.append(self.getPlayerByIndex(i))
		return players

	def getPlayersInSquad(self, team, squadId):
		indices = host.pmgr_getPlayersInSquad(team, squadId)
		players = []
		# NOTE: this uses getPlayerByIndex so we return cached player objects
		# whenever we can
		for i in indices: players.append(self.getPlayerByIndex(i))
		return players

	def getPlayerByIndex(self, index):
		# dep: this uses a cache so that all references to a certain player
		# index will always yield the same object, which is useful because you
		# can then test them for object equality
		valid = host.pmgr_isIndexValid(index)
		if not valid: 
			if self._pcache.has_key(index):
				if g_debug: print "Removed player index %d from player cache" % index
				del self._pcache[index]
			return None
		if not self._pcache.has_key(index):
			self._pcache[index] = Player(index)
		return self._pcache[index]
		
	def getNextPlayer(self, index):
		startIndex = index
		p = None
		index = index + 1
		while p == None and index != startIndex:
			p = self.getPlayerByIndex(index)
			index = index + 1
			if index > 255: index = 0
			if index > 63: index = 255
		
		if not p:
			return self.getPlayerByIndex(startIndex)
		else:
			return p

	def getNumberOfPlayersInTeam(self, team):
		players = self.getPlayers()
		inTeam = 0
		for p in players:
			if p.getTeam() == team:
				inTeam += 1
		
		return inTeam
		
	def getNumberOfAlivePlayersInTeam(self, team):
		players = self.getPlayers()
		inTeam = 0
		for p in players:
			if p.getTeam() == team and p.isAlive():
				inTeam += 1
		
		return inTeam
		
	# allows temporary disabling of the onPlayerScore event.
	def enableScoreEvents(self):
		return host.pmgr_enableScoreEvents(1)
	def disableScoreEvents(self):
		return host.pmgr_enableScoreEvents(0)
