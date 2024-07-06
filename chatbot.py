import sys
import random
import json 
import pickle
import numpy as np 
import spacy.lang
import keras.models as ke
import os
import subprocess
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

nlp = spacy.load('fr_core_news_md')

intents = json.loads(open("../conversation.json", encoding="utf-8").read()) 
words = pickle.load(open('../words.pkl', 'rb')) 
classes = pickle.load(open('../classes.pkl', 'rb')) 
model = ke.load_model('../chatbotmodel.keras') 

def clean_up_sentences(sentence): 
	sentence_words = nlp(sentence) 
	sentence_words = [word.lemma_  for word in sentence_words]
	return sentence_words 

def bagw(sentence): 
	sentence_words = clean_up_sentences(sentence) 
	bag = [0]*len(words) 
	for w in sentence_words: 
		for i, word in enumerate(words): 
			if word == w: 
				bag[i] = 1
	return np.array(bag) 

def predict_class(sentence): 
	bow = bagw(sentence) 
	res = model.predict(np.array([bow]), verbose=0)[0] 
	ERROR_THRESHOLD = 0.25
	results = [[i, r] for i, r in enumerate(res) 
			if r > ERROR_THRESHOLD] 
	results.sort(key=lambda x: x[1], reverse=True) 
	return_list = [] 
	for r in results: 
		return_list.append({'intent': classes[r[0]], 
							'probability': str(r[1])}) 
		return return_list 

def get_response(intents_list, intents_json): 
	tag = intents_list[0]['intent'] 
	list_of_intents = intents_json['intents'] 
	result = "" 
	for i in list_of_intents: 
		if i['tag'] == tag: 
			result = random.choice(i['responses']) 
			break
	return result 


message = ' '.join(sys.argv[1:])
ints = predict_class(message) 
res = get_response(ints, intents) 
print(res) 
