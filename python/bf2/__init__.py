import host
import sys

from bf2.Timer import Timer

class GameStatus:
	Playing = 1
	EndGame = 2
	PreGame = 3
	Paused = 4
	RestartServer = 5
	NotConnected = 6

playerManager = None
objectManager = None
triggerManager = None
gameLogic = None
serverSettings = None
gameServer = None

g_debug = 1


# set up singletons
import bf2.PlayerManager
import bf2.ObjectManager
import bf2.TriggerManager
import bf2.GameLogic
playerManager = bf2.PlayerManager.PlayerManager()
objectManager = bf2.ObjectManager.ObjectManager()
triggerManager = bf2.TriggerManager.TriggerManager()
gameLogic = bf2.GameLogic.GameLogic()
serverSettings = bf2.GameLogic.ServerSettings()
gameServer = bf2.GameLogic.GameServer()

# these are for wrapping purposes when converting c++ pointers into python objects
playerConvFunc = playerManager.getPlayerByIndex

class fake_stream:
	"""Implements stdout and stderr on top of BF2's log"""
	def __init__(self, name):
		self.name = name
		self.buf = ''

	def write(self, str):
		if len(str) == 0: return
		self.buf += str
		if str[-1] == '\n':
			host.log(self.name + ': ' + self.buf[0:-1])
			self.buf = ''

	def flush(self): pass
	def close(self): pass

class fake_stream2:
	"""Implements stdout and stderr on top of BF2's log"""
	def __init__(self, name):
		self.buf = [str(name), ': '] 

	def write(self, str):
		if len(str) == 0: return
		if str[-1] != '\n':
			self.buf.append (str)
		else:
			self.buf.append (str[0:-1])
			host.log("".join (self.buf))
			self.buf = [] 

	def flush(self): pass
	def close(self): pass

def init_module():
	# set up stdout and stderr to map to the host's logging function
	sys.stdout = fake_stream('stdout')
	sys.stderr = fake_stream('stderr')

	log = file('bf2python.log', 'w')
	sys.stdout = log
	sys.stderr = log

	import game.scoringCommon
	game.scoringCommon.init()
	
	try:
		import bf2.stats.stats
	except ImportError:
		print "__init__[87]: Official stats module not found."
	else:
		print "__init__[89]: Official stats module found."
		bf2.stats.stats.init()

	try:
		import bf2.stats.endofround
	except ImportError:
		print "__init__[95]: Endofround module not found."
	else:
		print "__init__[97]: Endofround module found."
		bf2.stats.endofround.init()


	if not gameLogic.isAIGame():
	
		try:
			import bf2.stats.snapshot
		except ImportError:
			print "__init__[106]: Snapshot module not found. %s" % ImportError
		else:
			print "__init__[108]: Snapshot module found."
			bf2.stats.snapshot.init()
		
		try:
			import bf2.stats.medals
		except ImportError:
			print "__init__[114]: Medal awarding module not found."
		else:
			print "__init__[116]: Medal awarding module found."
			bf2.stats.medals.init()
	
		try:
			import bf2.stats.rank
		except ImportError:
			print "__init__[122]: Rank awarding module not found."
		else:
			print "__init__[124]: Rank awarding module found."
			bf2.stats.rank.init()
		
	try:
		import bf2.stats.unlocks
	except ImportError:
		print "__init__[130]: Unlock awarding module not found."
	else:
		print "__init__[132]: Unlock awarding module found."
		bf2.stats.unlocks.init()
		
	try:
		import bf2.stats.fragalyzer_log
	except ImportError:
		print "__init__[138]: Fragalyzer log module not found."
	else:
		print "__init__[140]: Fragalyzer log module found."
		bf2.stats.fragalyzer_log.init()
	
#	try:
#		import bf2.stats.test
#	except ImportError:
#		print "__init__[146]: test module not found."
#	else:
#		print "__init__[148]: test module found."
#		bf2.stats.test.init()
