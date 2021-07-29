import sys
import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.neighbors import KNeighborsClassifier


df = pd.read_csv('flightdataset2.csv')

#filter out columns we don't want
df = df[["categorie_avion","code_aeroport_depat","code_aeroport_arrive","duree_TA","duree_TB","duree_TC","duree_TD","duree_TE","retard"]]

df = df.fillna({'duree_TE': 0})


train_x=df.drop('retard', axis=1)
train_y=df['retard']


#normalization
scaler = StandardScaler()
scaler.fit(train_x)

train_x = scaler.transform(train_x)


#Training the model
model = KNeighborsClassifier(n_neighbors=5)
model.fit(train_x, train_y)


catavion= sys.argv[1] 
origin= sys.argv[2] 
destination= sys.argv[3] 
t1= sys.argv[4] 
t2= sys.argv[5] 
t3= sys.argv[6] 
t4= sys.argv[7] 
t5= sys.argv[8] 

result = model.predict([[catavion,origin,destination,t1,t2,t3,t4,t5]])

print(result[0])
