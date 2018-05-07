from tkinter import *
from scraper import *

master = Tk()

variable = StringVar(master)
faculties = scrapeFaculty()

label = Label(master, text='Faculty Name')
label.pack()
listBox = Listbox(master)
listBox.pack()

for key in sorted(faculties):
    listBox.insert(END, key)

master.mainloop()
