from firebase import firebase
import updateFirebase
import scraper
import json
import requests

def main():
	url = 'https://guelph-courses.firebaseio.com/'
	print 'updating buildings...'
	buildings = scraper.getBuildingCodes();
	i = 0
	for building in buildings:
		print 'updating ' + building + '...'
		build = dict()
		build[building] = buildings[building]
		json_building = json.dumps(build)
		#if i == 0:
		#	updateFirebase.firebaseUpdate('buildings', json_building, url)
		#else:
		#	updateFirebase.firebaseWrite('buildings', json_building, url)
		i = i + 1
	
	print '\n\nupdating exam locations...'
	exam_info = scraper.getExams()
	i = 0
	for exam in exam_info:
		print 'updating ' + exam + '...'
		exam_loc = dict()
		exam_loc[exam] = exam_info[exam]
		json_exam = json.dumps(exam_loc)
		if i == 0:
			updateFirebase.firebaseUpdate('exams', json_exam, url)
		else:
			updateFirebase.firebaseWrite('exams', json_exam, url)
		i = i + 1	

	print '\n\nupdating terms...'
	terms = scraper.getAvailableTerms()
	#updateFirebase.firebaseUpdate("terms", terms, url)

	#print 'updating faculties and their classes'
	#faculty = scraper.getFaculties()
	#a = []
	#for name in faculty:
	#	print 'updating ' +  name  + '...'
	#	curr_faculty = scraper.getCourses(faculty[name])
	#	for course in curr_faculty:
	#		a.append(curr_faculty[course])
	#fb = firebase.FirebaseApplication('https://guelph-courses.firebaseio.com/')
	#updateFirebase.firebaseUpdate("classes", a, fb)

if __name__ == "__main__":
	main()
