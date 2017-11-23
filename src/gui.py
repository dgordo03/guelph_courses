from tkinter import *

master = Tk()

variable = StringVar(master)
arr = ['a','b','c']
i = 0

variable.set(arr[0])

menu = OptionMenu(master, variable, *arr)
menu.pack()

master.mainloop()
