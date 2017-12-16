from firebase import firebase
import json
import time


firebase = firebase.FirebaseApplication('https://guelph-courses.firebaseio.com/')


def firebaseGet(name):
    result = firebase.get(name, None)
    return result

def firebaseWrite(name, data):
    firebase.post(name, data)

def firebaseDelete(name, key):
    firebase.delete(name, key)

def firebaseUpdate(name, data):
    result = firebaseGet(name)
    if result == None:
        firebaseWrite(name, data)
    else:
        for key in result:
            deleteKey = key
        firebaseDelete(name, deleteKey)
        firebaseWrite(name, data)
