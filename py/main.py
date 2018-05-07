#from firebase import firebase
# import updateFirebase
import scraper
# import json
import requests
import MySQLdb

def updateBuildings():
	buildings = scraper.getBuildingCodes();
	db = MySQLdb.connect('localhost', 'admin', 'admin', 'information')
	cursor = db.cursor()
	# Drop table if it already exist using execute() method.
	cursor.execute("DROP TABLE IF EXISTS BUILDINGS")
	# Create table as per requirement
	sql = """CREATE TABLE BUILDINGS (
	         BUILDING  CHAR(200) NOT NULL,
	         ACRONYM  CHAR(20) NOT NULL )"""
	cursor.execute(sql)
	for building in buildings:
		if (not building):
			continue
		sql = "INSERT INTO BUILDINGS(BUILDING, \
		       ACRONYM) \
		       VALUES ('%s', '%s' )" % \
		       (buildings[building], building)
		print sql;
		try:
			# Execute the SQL command
			cursor.execute(sql)
			# Commit your changes in the database
			db.commit()
		except:
			# Rollback in case there is any error
			db.rollback()
	db.close()


def updateExams():
	exam_info = scraper.getExams()
	db = MySQLdb.connect('localhost', 'admin', 'admin', 'information')
	cursor = db.cursor()
	# Drop table if it already exist using execute() method.
	cursor.execute("DROP TABLE IF EXISTS EXAMS")
	# Create table as per requirement
	sql = """CREATE TABLE EXAMS (
	         BUILDING  CHAR(20) NOT NULL,
	         ROOM  CHAR(20) NOT NULL,
			 DATE  CHAR(20) NOT NULL,
			 START  CHAR(20) NOT NULL,
			 END  CHAR(20) NOT NULL,
			 COURSE  CHAR(20) NOT NULL,
			 INSTRUCTOR CHAR(100) )"""
	cursor.execute(sql)
	for building in exam_info:
		# Building
		for classroom in exam_info[building]:
			# Classroom
			for date in exam_info[building][classroom]:
				# Exam Date
				for exam in exam_info[building][classroom][date]:
					sql = "INSERT INTO EXAMS(BUILDING, \
					       ROOM, DATE, START, END, COURSE, INSTRUCTOR) \
					       VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s' )" % \
					       (building, classroom, date, exam['start'], exam['end'], exam['course'].replace(" ", ""), exam['instructor'].replace('\'', '-'))
					print sql;
					try:
						# Execute the SQL command
						cursor.execute(sql)
						# Commit your changes in the database
						db.commit()
					except:
						# Rollback in case there is any error
						db.rollback()
	db.close()


def updateCurrentCourseInfo():
	terms = scraper.getSearchCriteria()
	db = MySQLdb.connect('localhost', 'admin', 'admin', 'information')
	cursor = db.cursor()
	# Drop table if it already exist using execute() method.
	for info in terms:
		sql = "DROP TABLE IF EXISTS " + info.upper()
		cursor.execute(sql)
		sql = """CREATE TABLE """ + info.upper() + """ (
		""" + info.upper()[:-1] + """  CHAR(20) NOT NULL )"""
		cursor.execute(sql)
		for i in range(0, len(terms[info])):
			sql = "INSERT INTO %s(%s) \
			       VALUES ('%s' )" % \
			       (info.upper(), info.upper()[:-1], terms[info][i])
			print sql
			try:
				# Execute the SQL command
				cursor.execute(sql)
				# Commit your changes in the database
				db.commit()
			except:
				# Rollback in case there is any error
				db.rollback()
	db.close()


def getFaculties():
	faculties = scraper.getFaculties()
	db = MySQLdb.connect('localhost', 'admin', 'admin', 'information')
	cursor = db.cursor()
	# Drop table if it already exist using execute() method.
	cursor.execute("DROP TABLE IF EXISTS FACULTIES")
	# Create table as per requirement
	sql = """CREATE TABLE FACULTIES (
	         FACULTY  CHAR(200) NOT NULL,
			 FACULTY_ACR  CHAR(10) NOT NULL,
			 COURSE  CHAR(200) NOT NULL,
			 COURSE_ACR  CHAR(100) NOT NULL )"""
	cursor.execute(sql)
	for faculty in faculties:
		print 'updating ' + faculty['name']
		curr_faculty = scraper.getCourses(faculty['href'])
		for course in curr_faculty:
			sql = "INSERT INTO FACULTIES(FACULTY, \
			       FACULTY_ACR, COURSE, COURSE_ACR) \
			       VALUES ('%s', '%s' , '%s', '%s' )" % \
			       (faculty['name'], faculty['acrnm'], course, course.split(" ")[0])
			try:
				# Execute the SQL command
				cursor.execute(sql)
				# Commit your changes in the database
				db.commit()
			except:
				# Rollback in case there is any error
				db.rollback()
	db.close()
	# for name in faculty:
	# 	curr_course = []
	# 	print '\n\n\nupdating ' +  name  + '...'
	# 	curr_faculty = scraper.getCourses(faculty[name])
	# 	for course in curr_faculty:
	# 		curr_course.append(curr_faculty[course])
	# 		print course
	# 		print curr_faculty[course]


def main():
	# print 'updating buildings...'
	# updateBuildings()

	# print '\n\nupdating exam locations...'
	# updateExams()

	# print '\n\nupdating current class information...'
	# updateCurrentCourseInfo()

	# print '\n\nupdating faculties'
	# getFaculties()
	return


if __name__ == "__main__":
	main()
