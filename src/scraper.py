import requests
from BeautifulSoup import BeautifulSoup
from selenium import webdriver

base_url = "https://www.uoguelph.ca/registrar/calendars/undergraduate/\
2017-2018/c12/"
exam_base = "https://www.uoguelph.ca/registrar/scheduling/"


def getAvailableTerms():
	driver = webdriver.PhantomJS()
	driver = webdriver.PhantomJS()
	driver.get('https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TYPE=M&PID=CORE-WBMAIN&TOKENIDX')
	driver.find_element_by_class_name('WBST_Bars').click()
	driver.find_element_by_class_name('subnav')
	driver.find_element_by_xpath("//a[text()='Search for Sections']").click()
	elems = driver.find_element_by_id('VAR1').get_attribute('innerHTML')
	soup = BeautifulSoup(elems)
	values = []
	for option in soup.findAll('option'):
		if option['value']:
			values.append(option['value'])
	driver.quit()
	return values


def getBuildingCodes():
    url = exam_base + "buildingcodes-col"
    response = requests.get(url)
    html = response.content
    soup = BeautifulSoup(html)
    mainDiv = soup.find('div', attrs={'id': 'main'})
    containerDiv = mainDiv.find('div', attrs={'class': 'container'})
    contentDiv = containerDiv.find('div', attrs={'id': 'content'})
    buildings = {}
    for code in contentDiv.findAll('p'):
        full_name = code.text.replace('&nbsp', '')
        full_name = full_name.encode('utf8')
        temp_name = full_name.split('\xc2\xa0')
        build_code = temp_name[0]
        build_name = ''
        temp_name.remove(build_code)
        for name in temp_name:
            if name != '':
                build_name += name
        buildings[build_code] = build_name
    return buildings


def getExams():
    url = exam_base + "exam_fall"
    response = requests.get(url)
    html = response.content
    soup = BeautifulSoup(html)
    mainDiv = soup.find('div', attrs={'id': 'main'})
    containerDiv = mainDiv.find('div', attrs={'class': 'container'})
    contentDiv = containerDiv.find('div', attrs={'id': 'content'})
    table = contentDiv.find('table', attrs={'class': 'exams'})
    body = table.find('tbody')
    exams = dict()
    for row in body.findAll('tr'):
        curr_exam = row.findAll('td')
	date = curr_exam[2].text.replace('&nbsp', '')
        start = curr_exam[3].text.replace('&nbsp', '')
        end = curr_exam[4].text.replace('&nbsp', '')
	locations = curr_exam[6].text.replace('&nbsp', '').split(',')
	for i in range(0, len(locations)):
        	room = locations[i]
		if i > 0:
        		room = room.encode('utf8')
        		room = room.replace('\xc2\xa0 ', '')
		if not room:
			continue
		building = room.split(' ')[0]
		#3. check if building exists, then room, and add to list
		if building not in exams:
			room_info = dict()
			exam_info = dict()
			times = dict()
			exam_info[date] = []
			times[start] = end
			exam_info[date].append(times)
			room_info[room] = exam_info
			exams[building] = room_info
		else:
			#check if room exists, check if date exists, check if time exists
			if room not in exams[building]:
				exam_info = dict()
				times = dict()
				exam_info[date] = []
				times[start] = end
				exam_info[date].append(times)
				exams[building][room] = exam_info
			else:
				#check if date exists
				if date not in exams[building][room]:
					times = dict()
					exam_info = []
					times[start] = end
					exam_info.append(times)
					exams[building][room][date] = exam_info
				else:
					#date exists
					times = dict()
					exam_info = exams[building][room][date]
					times[start] = end
					exam_info.append(times)
					exams[building][room][date] = exam_info
    return exams

getExams()

def getCourses(page):
    url = base_url + page
    response = requests.get(url)
    html = response.content
    soup = BeautifulSoup(html)
    mainDiv = soup.find('div', attrs={'id': 'main'})
    contentDiv = mainDiv.find('div', attrs={'id': 'content'})
    all_courses = {}
    for courses in contentDiv.findAll('div', attrs={'class': 'course'}):
        curr_course = {}
        prereq_course = {}
        restr_course = {}
        table = courses.find('table')
        title_class = table.find('tr', attrs={'class': 'title'})
        title = title_class.find('a').text
        descr_class = table.find('tr', attrs={'class': 'description'})
        description = descr_class.find('td').text
        curr_course['description'] = description
        restr_class = table.find('tr', attrs={'class': 'restrictions'})
        if restr_class is not None:
            for restr in restr_class.findAll('a'):
                restriction = restr.text
                href = restr['href']
                restr_course[restriction] = href
        curr_course['restrictions'] = restr_course
        prereq_class = table.find('tr', attrs={'class': 'prereqs'})
        if prereq_class is not None:
            for prereq in prereq_class.findAll('a'):
                requisite = prereq.text
                href = prereq['href']
                prereq_course[requisite] = href
        curr_course['prerequisites'] = prereq_course
        all_courses[title] = curr_course
    return all_courses


def getFaculties():
    url = base_url + 'index.shtml'
    response = requests.get(url)
    html = response.content
    soup = BeautifulSoup(html)
    div = soup.find('div', attrs={'id': 'sidebar'})
    faculty = {}
    for unorderd_list in div.findAll('ul'):
        for list_item in unorderd_list.findAll('a'):
            href = list_item['href']
            name = list_item.text.replace('&nbsp', '')
            faculty[name] = href
    return faculty
