'''
@kenji-sakuramoto
?scrape soundcloud & mixcloud plays
?print out leftover plays needed for the order
*place "scplays.csv" in same folder
*1st column HTML, 2nd column current plays, 3rd column expected plays
'''

#load modules
from bs4 import BeautifulSoup
from pandas import *
#import scrapy
#import csv
#from datetime import datetime
try:
    # Python 3.0
    from urllib.request import urlopen
except ImportError:
    # Python 2
    from urllib2 import urlopen

#load excel 
''' 
with open('scplays.csv', 'r') as f:
    reader = csv.reader(f)
    sc_page = list(reader)
'''
sc_data = read_csv("scplays.csv")
sc_page = sc_data.ix[:,0]
sc_expected = sc_data.ix[:,3]

#list comprehension
#sc_page = [l[0] for l in sc_page]

#query & parse
data = []
x = 0
for page in sc_page:
    sc_page_query = urlopen(page)
    sc_page_parse = BeautifulSoup(sc_page_query, 'html.parser')

#find
    track = sc_page_parse.find('meta', property="og:title", content=True)
    plays = sc_page_parse.find('meta', property="soundcloud:play_count", content=True)
    data.append((track, plays))
    print(track["content"] if track else "No meta title given")
    print(plays["content"] if plays else "No meta title given")
    #sc_expected[x] = int(sc_expected[x]) - int(plays["content"])
    print(sc_expected[x])
    print('-------------------------------------------')
    x += 1

'''
#save file
with open('plays.csv', 'a') as csv_file:
    writeplays = csv.writer(csv_file)
    for track, plays in data:
        if plays is None:
            continue
        writeplays.writerow([datetime.now(), track["content"], plays["content"]])
'''
