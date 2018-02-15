'''
kenji-sakuramoto
scrape soundcloud & mixcloud plays
'''

#load modules
import time
import os
from bs4 import BeautifulSoup
import pandas as pd
try:
    # Python 3.0
    from urllib.request import urlopen
except ImportError:
    # Python 2
    from urllib2 import urlopen

#load excel 
df = pd.read_csv(os.path.join(os.path.dirname(__file__),"scplays.csv"), encoding="ISO-8859-1")
sc_url = df.ix[:,0]
sc_start = df.ix[:,1].fillna(-1)
sc_bought = df.ix[:,2]
sc_expected = df.ix[:,3].fillna(-1)
sc_last = df.ix[:,4].fillna(0)

#query & parse
print(time.ctime())

#loop through each url
x=0
for url in sc_url:
    try:
        sc_page_query = urlopen(url)
    except:
        print("Error", url)
        x += 1
        continue
    
    sc_page_parse = BeautifulSoup(sc_page_query, 'html.parser')
    
    #find in page
    if "soundcloud" in url:
        try:
            track = sc_page_parse.find('meta', property="og:title", content=True)
            plays = sc_page_parse.find('meta', property="soundcloud:play_count", content=True)
            plays = int(plays["content"])
        except:
            track = "Error"
            plays = "-1"
        #check if start or expected plays is empty
        if sc_start[x] == -1:
            sc_start[x] = plays
        if sc_expected[x] == -1:
            sc_expected[x] = sc_start[x]+sc_bought[x]
        print(url.replace("https://",""))
        print("Left: ",sc_expected[x]-plays,"\tSC: ",plays-sc_last[x])
        sc_last[x] = plays
    if "mixcloud" in url:
        try:
            track = sc_page_parse.find('meta', property="og:title", content=True)
            plays = int(sc_page_parse.find('li', {'data-tooltip':True}).text.strip().replace(',',''))
        except:
            track = "Error"
            plays = "-1"
        #check if start or expected plays is empty
        if sc_start[x] == -1:
            sc_start[x] = plays
        if sc_expected[x] == -1:
            sc_expected[x] = sc_start[x]+sc_bought[x]
        print(url.replace("https://www.",""))
        print("Left: ",sc_expected[x]-plays,"\tMC: ",plays-sc_last[x])
        sc_last[x] = plays
    x += 1

#write to file
df['start'] = pd.Series(sc_start, index=df.index[:len(sc_start)])
df['expected'] = pd.Series(sc_expected, index=df.index[:len(sc_expected)])
df['last'] = pd.Series(sc_last, index=df.index[:len(sc_last)])
df.to_csv(os.path.join(os.path.dirname(__file__),"scplays.csv"), encoding="ISO-8859-1", index=False)
