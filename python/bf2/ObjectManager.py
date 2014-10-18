import host


class ObjectManager:
	def __init__(self):
		print "ObjectManager created"

	def getObjectsOfType(self, type):
		return host.omgr_getObjectsOfType(type)

	def getObjectsOfTemplate(self, templ):
		return host.omgr_getObjectsOfTemplate(templ)

	def getRootParent(self, obj):
		parent = obj.getParent()
		
		if parent == None:
			return obj
			
		return self.getRootParent(parent)
