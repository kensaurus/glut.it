import numpy as np
import tensorflow as tf
import os
# Disable AVX/FMA
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

# Sigmoid function
def sigmoid(z):
    return 1 / (1 + np.exp(-z))

# Load data from CSV, last column is the y values
trainData = np.transpose(np.loadtxt(open("training.csv","rb"), delimiter=",", dtype='float32'))
testData = np.transpose(np.loadtxt(open("test.csv","rb"), delimiter=",", dtype='float32'))
xtrainData  = trainData[0:-1]
ytrainData = trainData[-1]
xtestData  = testData[0:-1]
ytestData = testData[-1]

# Dataset sanity check
print("Training Data X: ", xtrainData.shape)
print("Training Data Y: ", ytrainData.shape)

# Params
learning_rate = 0.1
batch_size = 1000

# TF input
W = tf.Variable(tf.random_uniform([1, len(xtrainData)], -1,1))
hypo = tf.matmul(W,xtrainData)
print("W: ", W.shape)
print("Hypothesis: ", hypo.shape)

# Optimizer
cost = tf.reduce_mean(tf.square(hypo - ytrainData))
train = tf.train.GradientDescentOptimizer(learning_rate).minimize(cost)

# Accuracy


# TF session
init = tf.global_variables_initializer()
sess = tf.Session()
sess.run(init)

# Optimize
for step in range(batch_size):
    sess.run(train)
    if step % 100 == 0:
        print ("Step: ", step, ", Cost: ", sess.run(cost), ", W: ",sess.run(W))
        trainAccuracy = np.equal(np.greater_equal(np.matmul(sess.run(W), xtrainData),0)*1,ytrainData)*1
        testAccuracy = np.equal(np.greater_equal(np.matmul(sess.run(W), xtestData), 0)*1,ytestData) * 1
        print("Training accuracy: ", 100*trainAccuracy.sum()/trainAccuracy.size, "%")
        print("Test accuracy: ", 100 * testAccuracy.sum() / testAccuracy.size, "%")
