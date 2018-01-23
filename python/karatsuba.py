#counter=[0]
def karatsuba(x,y):
    counter[0]+=1
    if len(str(x))==1 or len(str(y))==1:
        return x*y
    else:
        #Find size of number
        m = max(len(str(x)),len(str(y)))
        n = m//2
        #Split number
        a = x//10**n
        b = x%10**n
        c = y//10**n
        d = y%10**n
        #Karatsuba operation
        ac = karatsuba(a,c)
        bd = karatsuba(b,d)
        abcd = karatsuba(a+b,c+d)-ac-bd
        ans = ac*10**(2*n) + (abcd*10**n) + bd
        return ans
#print(counter[0])
