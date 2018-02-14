import requests
from BeautifulSoup import BeautifulSoup
from selenium import webdriver
import signal
import sys

def main():
    for i in range(len(sys.argv)):
        if "term=" in sys.argv[i]:
            term_t = sys.argv[i].split('=')[1]
        elif "number=" in sys.argv[i]:
            number_t = sys.argv[i].split('=')[1]
        elif "subject=" in sys.argv[i]:
            subject_t = sys.argv[i].split('=')[1]
        elif "level=" in sys.argv[i]:
            level_t = sys.argv[i].split('=')[1]
    options = webdriver.ChromeOptions()
    options.add_argument('headless')
    options.add_argument('window-size=1200x600')
    driver = webdriver.Chrome(chrome_options=options)

    driver.get('https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TYPE=M&PID=CORE-WBMAIN&TOKENIDX')
    driver.find_element_by_class_name('WBST_Bars').click()
    driver.find_element_by_class_name('subnav')
    driver.find_element_by_xpath("//a[text()='Search for Sections']").click()
    try:
        term_t
    except NameError:
        pass
    else:
        term = driver.find_element_by_id('VAR1')
        for option in term.find_elements_by_tag_name('option'):
            if term_t in option.text:
                option.click() # select() in earlier versions of webdriver
                break
    try:
        subject_t
    except NameError:
        pass
    else:
        subject = driver.find_element_by_id('LIST_VAR1_1')
        for option in subject.find_elements_by_tag_name('option'):
            if subject_t in option.text:
                option.click() # select() in earlier versions of webdriver
                break
    try:
        level_t
    except NameError:
        pass
    else:
        level = driver.find_element_by_id('LIST_VAR2_1')
        for option in level.find_elements_by_tag_name('option'):
            if level_t in option.text:
                option.click() # select() in earlier versions of webdriver
                break

    try:
        number_t
    except NameError:
        pass
    else:
        number = driver.find_element_by_id('LIST_VAR3_1')
        number.clear()
        number.send_keys(number_t)
        driver.find_element_by_class_name('shortButton').click()
        elems = driver.find_element_by_class_name('envisionWindow').get_attribute('innerHTML')

    soup = BeautifulSoup(elems)
    table = soup.find('table')
    body = table.find('tbody')
    for row in body.findAll('tr'):
        meeting = row.findAll('td', 'SEC_MEETING_INFO')
        if meeting:
            meeting = meeting[0].text.replace('&nbsp', '').split(',')
        faculty = row.findAll('td', 'SEC_FACULTY_INFO')
        if faculty:
            faculty = faculty[0].text.replace('&nbsp', '').split(',')
        capacity = row.findAll('td', 'LIST_VAR5')
        if capacity:
            capacity = capacity[0].text.replace('&nbsp', '').split(',')
        link = row.findAll('td', 'SEC_SHORT_TITLE')
        if link:
            link = link[0].text.replace('&nbsp', '').split(',')
        try:
            meeting
            faculty
            capacity
        except NameError:
            pass
        else:
            string = meeting
            string += faculty
            string += capacity
            print string

    driver.service.process.send_signal(signal.SIGTERM)
    driver.quit()

if __name__ == "__main__":
	main()
