import json
from pprint import pprint
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer

analyzer = SentimentIntensityAnalyzer()

with open('source/tweets_2016_us_second_presidencial_debate_sp.json') as f:
    data = json.load(f)
    
print(len(data["data"]))

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

#pprint(data)
def save():
    with open('source/tweets_2016_us_second_presidencial_debate_sp_plus.json', 'w') as file:
        json.dump(data, file)

add_attr()
save()