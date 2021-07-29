import sys
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.neighbors import KNeighborsClassifier
from sklearn.metrics import roc_auc_score
from sklearn.metrics import confusion_matrix
from sklearn.metrics import precision_score
from sklearn.metrics import recall_score
import matplotlib.pyplot as plt
from sklearn.metrics import roc_curve


df = pd.read_csv('flightdataset2.csv')

#print(df.head())

#print(df.shape)

#filter out columns we don't want
#df = df[["categorie_avion","code_aeroport_depat","code_aeroport_arrive","duree_TA","duree_TB","duree_TC","duree_TD","duree_TE","retard"]]

#Checking for missing values
#print(df.isnull().values.any())

#Number of missing values in each column
#print(df.isnull().sum())

#Rows with missing values
#print(df[df.isnull().values.any(axis=1)].head())

#NaNs replaced with 0 (replace missing values with 0)
df = df.fillna({'duree_TE': 0})

#verify if rows with missing values are replaced
#print(df[df.isnull().values.any(axis=1)].head())



#split the dataset into two , one for training and one for testing
train_x, test_x, train_y, test_y = train_test_split(df.drop('retard', axis=1), df['retard'], test_size=0.3, random_state=45)

#normalization
scaler = StandardScaler()
scaler.fit(train_x)

train_x = scaler.transform(train_x)
test_x = scaler.transform(test_x)


#Training the model
model = KNeighborsClassifier(n_neighbors=5)
model.fit(train_x, train_y)

#Testing the model
predicted = model.predict(test_x)
print("The accuracy of the model :",model.score(test_x, test_y))

probabilities = model.predict_proba(test_x)
print("Probabilities : \n ",probabilities)

#Generating an AUC score
print(" AUC score :",roc_auc_score(test_y, probabilities[:, 1]))

#Generating a confusion matrix
print("confusion matrix : ",confusion_matrix(test_y, predicted))

#Measuring precision
train_predictions = model.predict(train_x)
print("precision :",precision_score(train_y, train_predictions))

#Measuring recall
print("recall ",recall_score(train_y, train_predictions))


error = []

# Calculating error for K values between 1 and 40
for i in range(1,10):
    knn = KNeighborsClassifier(n_neighbors=i)
    knn.fit(train_x, train_y)
    pred_i = knn.predict(test_x)
    error.append(np.mean(pred_i != test_y))


plt.figure(figsize=(12, 6))
plt.plot(range(1, 10), error, color='red', linestyle='dashed', marker='o',
         markerfacecolor='blue', markersize=10)
plt.title('Error Rate K Value')
plt.xlabel('K Value')
plt.ylabel('Mean Error')
#plt.show()


result = model.predict([[1,227,228,23,33,42,0,0]])

print(result[0])

