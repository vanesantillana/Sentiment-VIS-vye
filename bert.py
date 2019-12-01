import numpy as np
from bert_embedding import BertEmbedding

bert_abstract = """sells arms to saudi arabia
arms bend at the elbow
wave your arms around"""
sentences = bert_abstract.split('\n')
bert_embedding = BertEmbedding()
#result = bert_embedding(sentences)

#first_sentence = result[0]

#print(result[0][0], result[0][1][1])

embs = bert_embedding(sentences)
tokens = embs[0][0]
embV = embs[0][1]
W = np.array(embV)

B = np.array([embV[0], embV[-1]])
Bi = np.linalg.pinv(B.T)
Wp = np.matmul(Bi,W.T)

print(Wp, tokens)

#bert_sum = bert_embedding(sentences, 'sum')
#print(bert_sum)