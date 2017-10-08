import os
import string
import re

"""
Renames files in directory
kenji
http://glut.it
"""

path = os.getcwd()
files = os.listdir(path)
removestr = input("Remove string...")
replacestr = input("Replace string....")
replacestr2 = input("with...")

for file in files:
    fname, ext = os.path.splitext(file)
    if ext is ".torrent":
        os.remove(file, fname + ext)
        continue
    fname = fname.replace(removestr, "")
    fname = fname.replace(replacestr, replacestr2)
    fname = fname.replace(".", " ").replace("_", " ")
    fname = re.sub(' +', ' ', fname)
    fname = fname.strip()
    fname = string.capwords(fname)
    os.rename(file, fname + ext)
    
os.system("pause")
