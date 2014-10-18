# ------------------------------------------------------------------------------
# omero 2006-02-27
# ------------------------------------------------------------------------------

import socket, string

CRLF = "\r\n"

def http_get(host, port = 80, document = "/"):

	try:
		http = miniclient(host,	port)

	except Exception, e:

		if e[0]	== 111:
			print	"MiniClient[17]: Connection refused by server %s on port %d" % (host,port)

		raise

	http.writeline("GET %s HTTP/1.1" % str(document))
	http.writeline("HOST: %s" % host)
	http.writeline("User-Agent: GameSpyHTTP/1.0")
	http.writeline("Connection: close") # do not keep-alive
	http.writeline("")
	http.shutdown() # be nice, tell the http server we're done sending the request
	
	# Determine Status
	status = string.split(http.readline())
	if status[0] != "HTTP/1.1":
		print "MiniClient[31]: Unknown status response (%s)" % str(status[0])
	
	try:
		status = string.atoi(status[1])
	except ValueError:
		print "MiniClient[36]: Non-numeric status code (%s)" % str(status[1])
	
	#Extract Headers
	headers = []
	while 1:
		line = http.readline()
		if not line:
			break
		headers.append(line)
	
	http.close() # all done

	#Check we got a valid HTTP response
	if status == 200:
		return http.read()
	else:
		return "E\nH\terr\nD\tHTTP Error\n$\tERR\t$"
	


def http_postSnapshot(host, port = 80, document = "/", snapshot = ""):
	#print "MiniClient[57]: %s:%s/%s \n %s" % (str(host),str(port),str(document),str(snapshot),)
	try:
		http = miniclient(host, port)

	except Exception, e:

		if e[0]	== 111:
			print	"MiniClient[64]: Connection refused by server %s on port %d" % (host,port)
		
		raise

	try:
		print "POST %s HTTP/1.1\n" % str(document)
		http.writeline("POST %s HTTP/1.1" % str(document))
		print "HOST: %s\n" % str(host)
		http.writeline("HOST: %s" % str(host))
		print "User-Agent: GameSpyHTTP/1.0\n"
		http.writeline("User-Agent: GameSpyHTTP/1.0")
		print "Content-Type: application/x-www-form-urlencoded\n"
		http.writeline("Content-Type: application/x-www-form-urlencoded")
		print "Content-Length: %s\n" % str(len(snapshot))
		http.writeline("Content-Length: %s" % str(len(snapshot)))
		print "Connection: close\n"
		http.writeline("Connection: close")
		print "\n"
		http.writeline("")
		print str(snapshot)
		http.writeline(str(snapshot))
		print "\n"
		http.writeline("")
		print "-----"
		http.shutdown() # be nice, tell the http server we're done sending the request

		# Check that SnapShot Arrives.
		# Determine Status
		status = string.split(http.readline())
		if status[0] != "HTTP/1.1":
			print "MiniClient[84]: Unknown status response (%s)" % str(status)
		
		try:
			status = string.atoi(status[1])
		except ValueError:
			print "MiniClient[89]: Non-numeric status code (%s)" % str(status[1])
		
		#Extract Headers
		headers = []
		while 1:
			line = http.readline()
			if not line:
				break
			headers.append(line)
			
		http.close() # all done
		
		if status == 200:
			print "MiniClient[102] SNAPSHOT Received: OK"
			returnCode = 1
		else:
			print "MiniClient[105] SNAPSHOT Received: ERROR"
			returnCode = 0
		
		return status

	except Exception, e:
		raise

class miniclient:
	"Client support class for simple Internet protocols."

	def __init__(self, host, port):
		"Connect to an Internet server."
		

		self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		self.sock.settimeout(30)

		try:
			self.sock.connect((host, port))
			self.file = self.sock.makefile("rb")

		except socket.error, e:

			#if e[0]	== 111:
			#	print	"Connection refused by server %s on port %d" % (host,port)
			raise


	def writeline(self, line):
		"Send a line to the server."
		
		try:
			# Updated to sendall to resolve partial data transfer errors
			self.sock.sendall(line + CRLF) # unbuffered write

		except socket.error, e:
			if e[0] == 32 : #broken pipe
				self.sock.close() # mutual close
				self.sock = None
			
			raise e

		except socket.timeout:
			self.sock.close() # mutual close
			self.sock = None
			raise

	def readline(self):
		"Read a line from the server.  Strip trailing CR and/or LF."
		
		s = self.file.readline()
		
		if not s:
			raise EOFError
		
		if s[-2:] == CRLF:
			s = s[:-2]
		
		elif s[-1:] in CRLF:
			s = s[:-1]
		
		return s


	def read(self, maxbytes = None):
		"Read data from server."
		
		if maxbytes is None:
			return self.file.read()
		
		else:
			return self.file.read(maxbytes)


	def shutdown(self):
		
		if self.sock:
			self.sock.shutdown(1)


	def close(self):
		
		if self.sock:
			self.sock.close()
			self.sock = None