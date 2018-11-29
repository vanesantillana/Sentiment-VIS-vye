import json
from pprint import pprint

with open('source/tweets_2016_us_second_presidencial_debate.json') as f:
    data = json.load(f)
    
print(len(data["data"]))

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

#pprint(data)
with open('source/tweets_2016_us_second_presidencial_debate_sp.json', 'w') as file:
    json.dump(data, file)
    print("termine")
