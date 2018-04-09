'''
kenji-sakuramoto
scrape soundcloud & mixcloud plays
'''

import time
import os
from bs4 import BeautifulSoup
import pandas as pd
from urllib.request import urlopen
 
def load_file(filename):
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), filename), encoding="ISO-8859-1")
    a_url = df.ix[:,0]
    b_start = df.ix[:,1].fillna(-1)
    c_bought = df.ix[:,2]
    d_expect = df.ix[:,3].fillna(-1)
    e_last = df.ix[:,4].fillna(0)
    return a_url, b_start, c_bought, d_expect, e_last

def write_file(filename, b_start, d_expect, e_last):
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), filename), encoding="ISO-8859-1")
    df['start'] = pd.Series(b_start, index=df.index[:len(b_start)])
    df['expected'] = pd.Series(d_expect, index=df.index[:len(d_expect)])
    df['last'] = pd.Series(e_last, index=df.index[:len(e_last)])
    df.to_csv(os.path.join(os.path.dirname(__file__), filename), encoding="ISO-8859-1", index=False)

def new_links(filename, add):
    df = pd.read_csv(os.path.join(os.path.dirname(__file__), filename), encoding="ISO-8859-1")    
    print(df.append(add, ignore_index=True))
    df.to_csv(os.path.join(os.path.dirname(__file__), filename), encoding="ISO-8859-1", index=False)
    
def parse_html(a_url, b_start, c_bought, d_expect, e_last):
    x=0
    for url in a_url:
        try:
            page_query = urlopen(url)
        except:
            print("Error", url)
            x += 1
            continue
         
        page_parse = BeautifulSoup(page_query, 'html.parser')
        #find in page
        if "soundcloud" in url:
            try:
                track = page_parse.find('meta', property="og:title", content=True)
                plays = page_parse.find('meta', property="soundcloud:play_count", content=True)
                plays = int(plays["content"])
            except:
                track = "Error"
                plays = "-1"
            #check if start or expected plays is empty
            if b_start[x] == -1:
                b_start[x] = plays
            if d_expect[x] == -1:
                d_expect[x] = b_start[x] + c_bought[x]
            print(url)
            print("Left: ",int(d_expect[x] - plays),"\tChange: ", int(plays - e_last[x]))
            e_last[x] = plays
        if "mixcloud" in url:
            try:
                track = page_parse.find('meta', property="og:title", content=True)
                plays = int(page_parse.find('li', {'data-tooltip':True}).text.strip().replace(',',''))
            except:
                track = "Error"
                plays = "-1"
            #check if start or expected plays is empty
            if b_start[x] == -1:
                b_start[x] = plays
            if d_expect[x] == -1:
                d_expect[x] = b_start[x] + c_bought[x]
            print(url.replace("www.",""))
            try:
                print("Left: ",int(d_expect[x] - plays),"\tChange: ", int(plays - e_last[x]))
            except:
                print("Error plays")
            e_last[x] = plays
        x += 1
    return b_start, d_expect, e_last
    
def main():
    ###
    filename = "scplays.csv"
    ###
    print(time.ctime())
    choice = input("(A)dd or (L)ist: ")
    if choice == "A" or "a":
        while True:
            add = {}
            add['HTML'] = input("Site: ")      
            add['expected'] = input("Expected plays: ")
            new_links(filename, add)
            if input("(C)ontinue") == "C" or 'c':
                return True
            else:
                return False
    if choice == "L" or "l":
        a, b, c, d, e = load_file(filename)
        b, d, e = parse_html(a, b, c, d, e)
        write_file(filename, b, d, e)
    
main()

