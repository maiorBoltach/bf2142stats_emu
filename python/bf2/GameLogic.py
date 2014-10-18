# game logic


import host

class GameLogic:
	def __init__(self):
		print "GameLogic created"

	def getModDir(self): return host.sgl_getModDirectory()
	def getMapName(self): return host.sgl_getMapName()
	def getWorldSize(self): return host.sgl_getWorldSize()
	def getTeamName(self, team): return host.sgl_getParam('teamName', team, 0)
	def isAIGame(self): return host.sgl_getIsAIGame()
	def getOverlayDir(self): return host.sgl_getOverlayDirectory()

	def sendClientCommand(self, playerId, command, args): return host.sgl_sendPythonEvent(playerId, command, args)
	def sendGameEvent(self, player, event, data): return host.sgl_sendGameLogicEvent(player.index, event, data)
	def sendMedalEvent(self, player, type, value, points, credits): return host.sgl_sendMedalEvent(player.index, type, value, points, credits)
	def sendRankEvent(self, player, rank, score): return host.sgl_sendRankEvent(player.index, rank, score)
	def sendHudEvent(self, player, event, data): return host.sgl_sendHudEvent(player.index, event, data)
	
	def sendServerMessage(self, playerId, message): return host.sgl_sendTextMessage(playerId, 10, 1, message, 0)
#	def sendMessage2Player(self, playerId, channel, type, message): return host.sgl_sendTextMessage(playerId, channel, type, message, 0)
	
	def getTicketState(self, team): return host.sgl_getParam('ticketState', team, 0)
	def setTicketState(self, team, value): return host.sgl_setParam('ticketState', team, value, 0)
	
	def getTickets(self, team): return host.sgl_getParam('tickets', team, 0)
	def setTickets(self, team, value): return host.sgl_setParam('tickets', team, value, 0)
	
	def getDefaultTickets(self, team): return host.sgl_getParam('startTickets', team, 0)
	
	def getTicketChangePerSecond(self, team): return host.sgl_getParam('ticketChangePerSecond', team, 0, 0)
	def setTicketChangePerSecond(self, team, value): return host.sgl_setParam('ticketChangePerSecond', team, value, 0)
	
	def getTicketLimit(self, team, id): return host.sgl_getParam('ticketLimit', team, id)
	def setTicketLimit(self, team, id, value): return host.sgl_setParam('ticketLimit', team, id, value)
	
	def getDefaultTicketLossPerMin(self, team): return host.sgl_getParam('defaultTicketLossPerMin', team, 0)
	def getDefaultTicketLossAtEndPerMin(self): return host.sgl_getParam('defaultTicketLossAtEndPerMin', 0, 0)
	
	def getWinner(self): return host.sgl_getParam('winner', 0, 0)
	def getVictoryType(self): return host.sgl_getParam('victoryType', 0, 0)
	
	def setHealPointLimit(self, value): return host.sgl_setParam('healScoreLimit', 0, value, 0)
	def setRepairPointLimit(self, value): return host.sgl_setParam('repairScoreLimit', 0, value, 0)
	def setGiveAmmoPointLimit(self, value): return host.sgl_setParam('giveAmmoScoreLimit', 0, value, 0)
	def setTeamDamagePointLimit(self, value): return host.sgl_setParam('teamDamageScoreLimit', 0, value, 0)
	def setTeamVehicleDamagePointLimit(self, value): return host.sgl_setParam('teamVehicleDamageScoreLimit', 0, value, 0)
	
	
class ServerSettings:
	def __init__(self):
		print "Serversettings created"
	
	def getTicketRatio(self): return host.ss_getParam('ticketRatio')
	def getTeamRatioPercent(self): return host.ss_getParam('teamRatioPct')
	def getMaxPlayers(self): return host.ss_getParam('maxPlayers')
	def getGameMode(self): return host.ss_getParam('gameMode')
	def getMapName(self): return host.ss_getParam('mapName')
	def getTimeLimit(self): return host.ss_getParam('timeLimit')
	def getScoreLimit(self): return host.ss_getParam('scoreLimit')
	def getAutoBalanceTeam(self): return host.ss_getParam('autoBalance')
	def getTKPunishEnabled(self): return host.ss_getParam('tkpEnabled')
	def getTKNumPunishToKick(self): return host.ss_getParam('tkpNeeded')
	def getTKPunishByDefault(self): return host.ss_getParam('tkpDefault')
	
	def getUseGlobalRank(self): return host.ss_getParam('globRank')
	def getUseGlobalUnlocks(self): return host.ss_getParam('globUnlocks')
	
	def getMaxRank(self): return host.ss_getParam('maxRank')
	
class GameServer:
	def __init__(self):
		print "GameServer created"
		
	def abortCurrentConnectionAttempt(self, clientId): return host.gs_abortCurrentConnectionAttempt(clientId)
