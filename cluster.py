from sentence_transformers import SentenceTransformer
from sklearn.cluster import AgglomerativeClustering
import json
import os

embedder = SentenceTransformer('bert-base-nli-mean-tokens')

# Corpus with example sentences
with open('source/data-100.json') as f:
    data = json.load(f)
comments = data["data"]
all_corpus = []
for i in comments:
    all_corpus.append(i['text'])

#print(all_corpus)


# Perform kmean clustering
result_jsn = {"name":"", "children":[]}

num_clusters = 2

def bert(corpusdata,num_clusters):
    corpus_embeddings = embedder.encode(corpusdata)
    clustering_model = AgglomerativeClustering(n_clusters=num_clusters)
    clustering_model.fit(corpus_embeddings)
    cluster_assignment = clustering_model.labels_

    clustered_sentences = [[] for i in range(num_clusters)]
    for sentence_id, cluster_id in enumerate(cluster_assignment):
        clustered_sentences[cluster_id].append(corpusdata[sentence_id])

    return clustered_sentences

def childrenToJson(data):
    res_json = {"name":"","children":[]}
    for i in data:
        one_child = {"name":i, "size":0}
        res_json['children'].append(one_child)
    return res_json


def re_cluster(corpus):
    result = []
    res_json = {"name":"","children":[]}
    first_bert = bert(corpus,num_clusters)
    for i, cluster in enumerate(first_bert):
        if(len(cluster)>2):
            ncluster,child=re_cluster(cluster)
            res_json["children"].append(child)
        else:
            ncluster = cluster
            res_json["children"].append(childrenToJson(ncluster))
        result.append(ncluster)
        
    return result,res_json

        #jsn = {"name":"", "children":[]}
        #for item in cluster:
        #    jsn["children"].append( {"name": item, "size": 0})
        #result_jsn["children"].append(jsn)

    #print("Cluster ", i+1)
    #print(cluster)
#print(result_jsn)
#with open('source/bert.json', 'w') as file:
#    json.dump(result_jsn, file)

endcluster,endchild = re_cluster(all_corpus)
print(endchild)
with open('source/bert.json', 'w') as file:
    json.dump(endchild, file)