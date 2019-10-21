import json
import re
from pprint import pprint
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import collections
import os

analyzer = SentimentIntensityAnalyzer()
pronouns = ["he", "her", "hers", "herself", "him", "himself", "his", "i", 
"it", "its", "me", "mine", "my", "myself", "our", "ours", "ourselves", 
"she", "thee", "their", "them", "themselves", "they", "thou", 
"thy", "thyself", "us", "we", "ye", "you","u", "your", "yours", "yourself",
"we"]
#tweets json
#data ={}
with open('source/tweets_2016_us_second_presidencial_debate_sp_plus.json') as f:
    data = json.load(f)
    
def covert_txt():
    con=1
    for i in data["data"]:
        filename = 'source/text/comment_'+str(con)+".txt"
        dirname = os.path.dirname(filename)
        if not os.path.exists(dirname):
            os.makedirs(dirname)
        text_file = open( filename, "w")
        text_file.write(i['text'])
        text_file.close()
        #save(, i['text'])
        con+=1
        
#print("Total de Comentarios: ",len(data["data"]))

def change_spanish():
    for i in range(0,len(data["data"])):
        sentiment = data["data"][i]["value"]
        #pprint(sentiment)
        if('Contented' == sentiment):
            data["data"][i]["value"] = "Contento"
        elif('Serene' == sentiment):
            data["data"][i]["value"] = "Sereno"
        elif('Relaxed' == sentiment):
            data["data"][i]["value"] = "Relajado"
        elif('Calm' == sentiment):
            data["data"][i]["value"] = "Calmado"
        
        elif('Lethargic' == sentiment):
            data["data"][i]["value"] = "Letargico"
        elif('Bored' == sentiment):
            data["data"][i]["value"] = "Aburrido"
        elif('Depressed' == sentiment):
            data["data"][i]["value"] = "Deprimido"
        elif('Sad' == sentiment):
            data["data"][i]["value"] = "Triste"
        
        elif('Upset' == sentiment):
            data["data"][i]["value"] = "Trastornado"
        elif('Stressed' == sentiment):
            data["data"][i]["value"] = "Estresado"
        elif('Nervous' == sentiment):
            data["data"][i]["value"] = "Nervioso"
        elif('Tense' == sentiment):
            data["data"][i]["value"] = "Tenso"

        elif('Alert' == sentiment):
            data["data"][i]["value"] = "Alerta"
        elif('Excited' == sentiment):
            data["data"][i]["value"] = "Emocionado"
        elif('Elated' == sentiment):
            data["data"][i]["value"] = "Exaltado"
        elif('Happy' == sentiment):
            data["data"][i]["value"] = "Feliz"

def add_attr():
    for i in data["data"]:
        vs = analyzer.polarity_scores(i['text'])
        i['pos']=vs['pos']
        i['neg']=vs['neg']
        i['neu']=vs['neu']
        i['compound']=vs['compound']
        #print(i)
def limpiar():
    all_words= []
    for i in data["data"]:
        sinchar = re.sub('[^a-zA-Z]', ' ', i['text'])
        texto_sinp = sinchar.lower()
        arr_comment = texto_sinp.split()
        all_words.extend(arr_comment)
        all_words = list(set(all_words))
    for pro in pronouns:
        if pro in all_words:
            all_words.remove(pro)
    print("Limpiando: ",len(all_words))
    with open('source/words.json', 'w') as file:
        json.dump(all_words, file)

#pprint(data)
def save(nombr, datito):
    with open('source/'+ nombr+".txt", 'w') as file:
        file.write(datito)
        #json.dump(datito, file)

#add_attr()
#save()

#limpiar()


# words
#with open('source/words.json') as f:
#    words = json.load(f)
#print("Total de Palabras: ",len(words))

vec_comment = []
def similitud():
    for i in data["data"]:
        sinchar = re.sub('[^a-zA-Z]', ' ', i['text'])
        texto_sinp = sinchar.lower()
        palabras = texto_sinp.split()
        vec_comment.append({'comentario': i['text'] , 'palabras': palabras})
    with open('source/palabras.json', 'w') as file:
        json.dump(vec_comment, file)

#similitud()
with open('source/palabras.json') as f:
    palabras = json.load(f)
print("Total de Palabras: ",len(palabras))

tenfirst  = palabras[0:10]
def distancia_comment ():
    for i in tenfirst:  # Primer comentario
        first = i['palabras']
        dist =[]
        for j in tenfirst: # Segundo comentario
            second = j['palabras']
            print("comentario1 ",first)
            print("comentario2 ",second)
            if second!= first: # Si son diferentes hallo su distancia
                d=0
                for ff in first:
                    if ff in second:
                        print("se repite: ", ff)
                        d+=1
                #comment = first + second
                #for el in comment: # sumo 1 si encuentra palabras iguales
                #    if comment.count(el)>1:
                #        print("se repite: ", el)
                #        d+=1
                dist.append(d)
            else:
                dist.append(-1)
        i['distancias'] = dist
    with open('source/palabras_plus.json', 'w') as file:
        json.dump(palabras, file)


#distancia_comment()

covert_txt()