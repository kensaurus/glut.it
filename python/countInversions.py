def openFile(name):
	import os
	f_path = os.path.dirname(__file__)
	f_path = os.path.join(f_path, name)
	try: 
		f = open(f_path, 'r')
		array=[]
		for line in f:
			array.append(int(line))
		f.close()	
		print("Length of array: ", len(array))
	except:
		print("File error!")
	return array

#Counts in time complexity O(n^2)
def countInvSlow(array):
	n = len(array)
	invCount = 0
	for i in range(n):
		for j in range(i+1, n):
			if(array[i] > array[j]):
				invCount += 1				
	return 0, invCount


#Counts in time complexity O(nlogn)
def countInvFast(array):
	invCount = 0
	if len(array) < 2: return array, 0
	else:
		mid = int(len(array)/2)
		#Recursion
		left, invCountLeft = countInvFast(array[:mid])
		right, invCountRight = countInvFast(array[mid:]) 
		merge, invCount = mergeSort(left, right)
		invCount += (invCountLeft + invCountRight)
	return merge, invCount

def mergeSort(left, right):
	invCount = 0
	i = 0
	j = 0
	temp = list()	
	while i < len(left) and j < len(right):
		if left[i] < right[j]:
			temp.append(left[i])
			i +=1
		#Inversion occurs when there are elements in left bigger than right
		elif right[j] < left[i]:
			temp.append(right[j])
			j +=1
			invCount += (len(left)-i)
	temp += left[i:]
	temp += right[j:]
	return temp, invCount

import time
array = openFile("IntegerArray.txt")
start_time = time.clock()

#Execute below
#array = [x for x in range(5000,0,-1)]
__, invCount = countInvFast(array)
#Execute above

print(invCount)
print("%s seconds elapsed" %round(time.clock()-start_time, 8))