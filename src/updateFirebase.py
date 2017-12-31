from firebase import firebase
import json
import time


def firebaseGet(name, url):
	fb = firebase.FirebaseApplication(url)
	result = fb.get(name, None)
	return result

def firebaseWrite(name, data, url):
	fb = firebase.FirebaseApplication(url)
	fb.post(name, data)

def firebaseDelete(name, key, url):
	fb = firebase.FirebaseApplication(url)
	fb.delete(name, key)

def firebaseUpdate(name, data, url):
	result = firebaseGet(name, url)
	if result == None:
		firebaseWrite(name, data, url)
	else:
		for key in result:
			deleteKey = key
			firebaseDelete(name, deleteKey, url)
			firebaseWrite(name, data, url)
