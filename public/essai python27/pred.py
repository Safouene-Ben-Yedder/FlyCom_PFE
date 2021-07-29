import sys
import pandas as pd
import numpy as np
from sklearn.neighbors import KNeighborsClassifier


df = pd.read_csv('flightdataset2.csv')

#filter out columns we don't want
df = df[["categorie_avion","code_aeroport_depat","code_aeroport_arrive","duree_TA","duree_TB","duree_TC","duree_TD","duree_TE","retard"]]

df = df.fillna({'duree_TE': 0})

#The DataFrame with indicator columns
df = pd.get_dummies(df, columns=['code_aeroport_depat', 'code_aeroport_arrive'])

df = pd.get_dummies(df, columns=['categorie_avion'])

train_x=df.drop('retard', axis=1)
train_y=df['retard']

#Training the model
model = KNeighborsClassifier(n_neighbors=5)
model.fit(train_x, train_y)

def predict_delay(cat_avion, origin, destination, ta, tb, tc, td, te):

    input = [{'duree_TA': ta,
              'duree_TB': tb,
              'duree_TC': tc,
              'duree_TD': td,
              'duree_TE': te,
              'code_aeroport_depart_AA': 1 if origin == 'AA' else 0,
              'code_aeroport_depart_AD': 1 if origin == 'AD' else 0,
              'code_aeroport_arrive_AB': 1 if destination == 'AB' else 0,
              'code_aeroport_arrive_AC': 1 if destination == 'AC' else 0,
              'code_aeroport_arrive_AE': 1 if destination == 'AE' else 0,
              'categorie_avion_CAT1': 1 if cat_avion == 'CAT1' else 0,
              'categorie_avion_CAT2': 1 if cat_avion == 'CAT2' else 0,
              'categorie_avion_CAT3': 1 if cat_avion == 'CAT3' else 0}]

    return model.predict(pd.DataFrame(input))


result = predict_delay('CAT1','AA','AB',23,33,42,0,0)

print(result[0])
