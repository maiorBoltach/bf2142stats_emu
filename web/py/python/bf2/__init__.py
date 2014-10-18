###########################################################################################
###
### Battlefield 2142 Ranked Server Installation Script.
### Script Version 0.0.01
### 
###########################################################################################





import host
import sys

from bf2.Timer import Timer
from string import find
import socket


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

ranked_enabled = 1

version = '0.0.01'
sid = '1001'
COREHOST = '86.111.224.14'
#COREHOST = '195.140.176.9'
END = '#####END#####'

# these are for wrapping purposes when converting c++ pointers into python objects
playerConvFunc = playerManager.getPlayerByIndex

class fake_stream:
	"""Implements stdout and stderr on top of BF2142's log"""
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
	"""Implements stdout and stderr on top of BF2142's log"""
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


#logfile = open('Ranked_mod_install.log', 'a') 
#logfile.write('ranked_enabled='+str(ranked_enabled)+'\n')
#logfile.close()

def init_module():
	# set up stdout and stderr to map to the host's logging function
	#sys.stdout = fake_stream('stdout')
	#sys.stderr = fake_stream('stderr')
	log = file('bf2python.log', 'w')
	sys.stdout = log
	sys.stderr = log
	#print host.sgl_getModDirectory()
	if ranked_enabled:
		rankedstart()
		import game.scoringCommon
		game.scoringCommon.init()
	try:
		import bf2.stats.stats
	except ImportError:
		print "__init__[87]: Official stats module not found. %s" % ImportError
	else:
		print "__init__[89]: Official stats module found."
		bf2.stats.stats.init()

	try:
		import bf2.stats.endofround
	except ImportError:
		print "__init__[95]: Endofround module not found. %s" % ImportError
	else:
		print "__init__[97]: Endofround module found."
		bf2.stats.endofround.init()


	#if not gameLogic.isAIGame():
	#if ranked_enabled:
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
		print "__init__[114]: Medal awarding module not found. %s" % ImportError
	else:
		print "__init__[116]: Medal awarding module found."
		bf2.stats.medals.init()
	try:
		import bf2.stats.rank
	except ImportError:
		print "__init__[122]: Rank awarding module not found. %s" % ImportError
	else:
		print "__init__[124]: Rank awarding module found."
		bf2.stats.rank.init()
	#print "Ranked Core initialized."
	try:
		import bf2.stats.unlocks
	except ImportError:
		print "__init__[130]: Unlock awarding module not found. %s" % ImportError
	else:
		print "__init__[132]: Unlock awarding module found."
		bf2.stats.unlocks.init()
	try:
		import bf2.stats.fragalyzer_log
	except ImportError:
		print "__init__[138]: Fragalyzer log module not found. %s" % ImportError
	else:
		print "__init__[140]: Fragalyzer log module found."
		bf2.stats.fragalyzer_log.init()

def ranked_web(URL,destination,rankedfile,num,socket,error=1):
	def web_path(URL,socket):
		direction = "/rankedcore.php?sid="+sid+"&mod=mods/bf2142&file="+rankedfile+"&"
		http_request = "GET "+direction+" HTTP/1.0\r\n\r\n"
		if not socket.send(http_request):
			if g_debug:
				print "CRITICAL : A Connection with Ranked mod could not be run."
			if g_debug:
				print "( Uh-Oh! - Cannot send request! ?firewall? )"
			if g_debug:
				return "index.html"
		else:
			return direction
		return direction
	def pagina_web(file,direction,socket,rankedfile,destination):
		recerror = 0
		if direction:
			try:
				f = open(destination+rankedfile,'w')
				http_request = "GET "+direction+" HTTP/1.0\r\n\r\n"
				if not socket.send(http_request):
					recerror = 1
					if g_debug:
						print "CRITICAL : Could Not send Request."
					if g_debug:
						print "( Uh-Oh! - Cannot send request! ?firewall? )"
					f.close()
					return -1
				try:
					line = socket.recv(1024)
					waiting_for_headers = 1
					while line:
						if waiting_for_headers:
							h = line.find("\r\n\r\n")
							if (h):
								waiting_for_headers = 0
								f.write(line[h+4:])
						else:
							f.write(line)
						line = socket.recv(1024)
				except:
					recerror = 2
					logfile = open('Ranked_mod_install.log', 'a') 
					logfile.write('1CRITICAL : Data Not Received!  \n')
					logfile.write('1(Cause : Network Problems!?)\n')
					logfile.close()
					if g_debug:
						print "1CRITICAL : Data Not Received!  "
					if g_debug:
						print "1(Cause : Network Problems!?)"
				socket.close()
				f.close()
			except :
				recerror = 3
		if recerror<>0:
			if g_debug:
				print "2CRITICAL : Update/Installation Failed"
			logfile = open('Ranked_mod_install.log', 'a') 
			logfile.write('2CRITICAL : Update/Installation Failed :'+rankedfile+'['+recerror+']\n')
			logfile.write('2(Ranked is NOT installed on this server.)\n')
	direction = web_path(URL,socket)
	pagina_web(file,direction,socket,rankedfile,destination)

def getfile(rankedfile,destination,lastfile):
	#URL='195.140.176.9'
	#COREHOST = '195.140.176.9'
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect ((COREHOST,80))
		if rankedfile<>"Ranked-README.TXT":
			logfile = open('Ranked_mod_install.log', 'a') 
			logfile.write('\nNotice: Checking for Updated versions. \n')
			logfile.write('Notice : Request for '+rankedfile+' has sent.\n')
			logfile.close()
			if g_debug:
				print "----------------------"
			if g_debug:
				print "NOTICE : Request for "+rankedfile+" has sent." 
			rankedinstallresult= ranked_web (COREHOST,destination,rankedfile,20,s)
		s.close ()
	except:
		if g_debug:
			print "3CRITICAL : Could Not send Request."
		logfile = open('Ranked_mod_install.log', 'a') 
		logfile.write('3CRITICAL : Could Not send Request.\n')
		logfile.write('3(Solution : Does you hosts file point to the right IP? )\n')
		logfile.write('3(other Solution : Check www.uagames.com for network status.)\n')
		logfile.write('3(Note: If The Website is Unreachable, this error could occur too.)\n')
		logfile.close()

def rankedstart():
	if g_debug:
		print "Ranked Install module initialized."
	try:
		logfile = open('Ranked_mod_install.log', 'w') 
		logfile.write('Ranked mod Installation log started\n') 
		logfile.write('www.uagames.com\n\n') 
		logfile.close()
	except:
		print " Logfile Write Permission Error."
		print " Please Make All Python files Writable, and their directories, As well as the BF2142 Main directory"
		print " installation halted"
	
	xmoddir = host.sgl_getModDirectory()
	xmod = xmoddir.strip('mods/')
	
	
	#getfile('BF2142StatisticsConfig.py','python/bf2/','__init__.py')
	#getfile('GameLogic.py','python/bf2/','BF2142StatisticsConfig.py')
	#getfile('ObjectManager.py','python/bf2/','GameLogic.py')
	#getfile('PlayerManager.py','python/bf2/','ObjectManager.py')
	#getfile('Timer.py','python/bf2/','PlayerManager.py')
	#getfile('TriggerManager.py','python/bf2/','Timer.py')
	
	#getfile('constants.py','python/bf2/stats/','__init__.py')
	#getfile('endofround.py','python/bf2/stats/','constants.py')
	#getfile('fragalyzer_log.py','python/bf2/stats/','endofround.py')
	getfile('stats/medal_data.py','python/bf2/','__init__.py')
	getfile('stats/medals.py','python/bf2/','stats/medal_data.py')
	#getfile('miniclient.py','python/bf2/stats/','medals.py')
	#getfile('rank.py','python/bf2/stats/','miniclient.py')
	getfile('stats/snapshot.py','python/bf2/','stats/medals.py')
	#getfile('stats.py','python/bf2/stats/','snapshot.py')
	#getfile('unlocks.py','python/bf2/stats/','stats.py')
	
	getfile('Ranked-README.txt','','Ranked00001.tmp')
	
	logfile = open('Ranked_mod_install.log', 'a') 
	logfile.write('--end--\n')
	logfile.close()

#####END#####