from sentence_transformers import SentenceTransformer
from sklearn.cluster import AgglomerativeClustering

embedder = SentenceTransformer('bert-base-nli-mean-tokens')

# Corpus with example sentences
corpus = ['A man is eating a food.',
          'A man is eating a piece of bread.',
          'A man is eating pasta.',
          'The girl is carrying a baby.',
          'The baby is carried by the woman',
          'A man is riding a horse.',
          'A man is riding a white horse on an enclosed ground.',
          'A monkey is playing drums.',
          'Someone in a gorilla costume is playing a set of drums.',
          'A cheetah is running behind its prey.',
          'A cheetah chases prey on across a field.'
          ]
corpus_embeddings = embedder.encode(corpus)

# Perform kmean clustering
num_clusters = 2
result_jsn = {'name':'', 'children':[]}

def clustering(corpus_t):
    clustering_model = AgglomerativeClustering(n_clusters=num_clusters)
    clustering_model.fit(corpus_t)
    cluster_assignment = clustering_model.labels_

    clustered_sentences = [[] for i in range(num_clusters)]
    for sentence_id, cluster_id in enumerate(cluster_assignment):
        clustered_sentences[cluster_id].append(corpus[sentence_id])
    
    return clustered_sentences

def insert_clusters(all_corpus):
    if(len(all_corpus)<=2):
        return {'name':'', 'children': [ {"name": "BetweennessCentrality", "size": 3534},{"name": "LinkDistance", "size": 5731} ]}
    else:
        two_clusters = clustering(all_corpus)
        insert_clusters(two_clusters[0])
        
#result_jsn['children'].append({'name':'', 'children': clustering(cluster)})
result_jsn = insert_clusters(corpus_embeddings)
print(result_jsn)
#for i, cluster in enumerate(clustered_sentences):
#    print("Cluster ", i+1)
#    print(cluster)
#    print("")


