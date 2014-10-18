import host

class TriggerManager:

	def createRadiusTrigger(self, object, callback, objName, radius, data=None):
		return host.trig_create(object, callback, objName, radius, 0, data)

	def createHemiSphericalTrigger(self, object, callback, objName, radius, data=None):
		return host.trig_create(object, callback, objName, radius, 1, data)

	def destroyAllTriggers(self):
		host.trig_destroyAll()

	def destroy(self, trig_id):
		host.trig_destroy(trig_id)

	def getObjects(self, trig_id):
		return host.trig_getObjects(trig_id)

