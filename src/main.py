import updateFirebase
import scraper


def main():
	print 'updating exam information...'
	exam_info = scraper.examInformation()
	updateFirebase.firebaseUpdate("exams", exam_info)


if __name__ == "__main__":
	main()
