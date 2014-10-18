
import host

class Timer:

	def __init__(self, targetFunc, delta, alwaysTrigger, data=None):
		self.targetFunc = targetFunc
		self.data = data
		self.time = host.timer_getWallTime() + delta
		self.interval = 0.0
		self.alwaysTrigger = alwaysTrigger
		host.timer_created(self)

	def __del__(self):
		print "timer object destroyed (rc 0)"

	def destroy(self):
		host.timer_destroy(self)

	def getTime(self):
		return self.time

	def setTime(self, time):
		self.time = time

	def setRecurring(self, interval):
		self.interval = interval

	def onTrigger(self):
		self.targetFunc(self.data)
		
