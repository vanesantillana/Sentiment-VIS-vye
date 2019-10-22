import nltk
import json
import os

with open('source/data-100.json') as f:
    data = json.load(f)
#print(data)
vars = []
corr = []
comments = data["data"]
positive = data["ranges"]['children'][0]['children']
neutral = data["ranges"]['children'][1]['children']
negativo = data["ranges"]['children'][2]['children']
def get_color(val):
    for element in positive:
        if element['name'] == val:
            return element['color']
    for element in neutral:
        if element['name'] == val:
            return element['color']
    for element in negativo:
        if element['name'] == val:
            return element['color']
            
def similitud():
    for i in comments:
        first = i['text']
        corr_f = []
        vars.append([first, i['value'], get_color(i['value'])])
        print(first)
        for j in comments:
            second = j['text']
            jd = nltk.jaccard_distance(set(first), set(second))
            corr_f.append(round(1-jd,2))
        corr.append(corr_f)
        #print(corr_f)

similitud()


with open('source/vis-100.json') as f:
    vis = json.load(f)

vis['vars'] = vars
vis['corr'] = corr

with open('source/vis-100.json', 'w') as file:
    json.dump(vis, file)


