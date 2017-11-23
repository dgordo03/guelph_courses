import requests
from BeautifulSoup import BeautifulSoup

url='https://www.uoguelph.ca/registrar/calendars/undergraduate/2017-2018/c12/index.shtml'
response = requests.get(url)
html = response.content
soup = BeautifulSoup(html)
table = soup.find('div', attrs={'id':'sidebar'})

course_descr = {}

for unorderd_list in table.findAll('ul'):
    for list_item in unorderd_list.findAll('a'):
        href = list_item['href']
        name = list_item.text.replace('&nbsp', '')
        course_descr[name] = href

