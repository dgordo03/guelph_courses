import requests
from BeautifulSoup import BeautifulSoup
from selenium import webdriver
import signal

def main():
    options = webdriver.ChromeOptions()
    options.add_argument('headless')
    options.add_argument('window-size=1200x600')
    driver = webdriver.Chrome(chrome_options=options)

    driver.get('https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TYPE=M&PID=CORE-WBMAIN&TOKENIDX')
    driver.find_element_by_class_name('WBST_Bars').click()
    driver.find_element_by_class_name('subnav')
    driver.find_element_by_xpath("//a[text()='Search for Sections']").click()
    term = driver.find_element_by_id('VAR1')
    print '\n\n\n\n\n'
    for option in term.find_elements_by_tag_name('option'):
        if option.text == 'W18 - Winter 2018':
            option.click() # select() in earlier versions of webdriver
            break
    subject = driver.find_element_by_id('LIST_VAR1_1')
    for option in subject.find_elements_by_tag_name('option'):
        if option.text == 'ENGG - Engineering':
            option.click() # select() in earlier versions of webdriver
            break
    level = driver.find_element_by_id('LIST_VAR2_1')
    for option in level.find_elements_by_tag_name('option'):
        if option.text == '300 - Third Year':
            option.click() # select() in earlier versions of webdriver
            break
    number = driver.find_element_by_id('LIST_VAR3_1')
    number.clear()
    number.send_keys("3100")
    driver.find_element_by_class_name('shortButton').click()
    elems = driver.find_element_by_class_name('envisionWindow').get_attribute('innerHTML')

    soup = BeautifulSoup(elems)
    table = soup.find('table')
    body = table.find('tbody')
    for row in body.findAll('tr'):
        print row

    driver.service.process.send_signal(signal.SIGTERM)
    driver.quit()


if __name__ == "__main__":
	main()
