from xml.dom import minidom

xmldoc = minidom.parse('text500.xml')
itemlist = xmldoc.getElementsByTagName('vertex')
print(len(itemlist))


iter = 0
for item in itemlist:
    allabels = item.getElementsByTagName('label')
    allscalares = item.getElementsByTagName('scalar')
    iter +=1
    for s in allabels:
        if (s.attributes['name'].value == "title") & (s.attributes['value'].value != ""):
            print("su text "+ str(iter))

    for s in allscalares:
        if s.attributes['name'].value == "cdata":
            print("su val "+ str(iter),s.attributes['value'].value)
        
