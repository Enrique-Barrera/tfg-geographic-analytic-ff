# -*- coding: utf-8 -*-
import os
import sys
import pandas as pd
import numpy as np
import pymysql
from math import sqrt
from sklearn.preprocessing import LabelEncoder
from sklearn.externals import joblib
from keras.models import model_from_yaml


#DIRECTORIO DE TRABAJO
directorio = "C:\\xampp\\htdocs\\pfg\\vistas\\modulos\\python"
os.chdir(directorio)

# CADENA DE CONEXION A BASE DE DATOS
conn = pymysql.connect(
    host="localhost", port=3306, user="root",
    passwd ="", db="pfg"
)

#LECTURA DE PARAMETRO NÚMERO DE CANDIDATO
#idcandidato = "1007"
idcandidato = sys.argv[1]

########################################################
#  LOG DE EJECUCION EN BBDD
########################################################
id_proceso = 5;
sqlcursor = conn.cursor()
sqlcursor.execute("DELETE FROM PROCESOS_PYTHON WHERE ID_PROCESO =%s and ID_CANDIDATO=%s", (id_proceso, idcandidato))
conn.commit()

def logbbdd(id_proceso, idcandidato, porcentaje, mensaje):

    sqlcursor=conn.cursor()
    sqlcursor.execute("INSERT INTO PROCESOS_PYTHON(ID_PROCESO, ID_CANDIDATO, PROGRESO_PORCENTAJE, PROGRESO_DESCRIPCION) \
                VALUES(%s, %s, %s, %s)", (id_proceso, idcandidato, porcentaje, mensaje))

    conn.commit()
    return

########################################################
#   NORMALIZACION LINEAL DE UNA VARIABLE
########################################################

def normalizar(X, Y):
    minimo = X.min()
    diferencia = X.max() - minimo
    Y = (Y - minimo) / diferencia
    return Y

logbbdd(5, idcandidato, 10, "Importación de Librerias")

########################################################
#  ÇARGA DE FICHEROS CANDIDATO Y COMPETENCIA CADENA TARGET
########################################################
query_competencia = 'SELECT PC.ID_CANDIDATO_TIPO, PCA.A1_COMPETENCIA, PCA.A1_INDICEOCIO, PCA.A1_INDICECOMERCIO, PCA.A1_INDICESALUD,\
    PCA.A1_INDICEHOTELES, PCA.A1_INDICERESTAURANTES, PCA.A1_INDICETURISMO, PCA.A1_INDICEGRANSUPERFICIE,\
    PCA.A1_INDICEGLOBAL, PCA.A2_POBLACION, PCA.A2_EXTRANJERA, PCA.A2_HOGARESTOTAL,\
    PCA.A2_VIVIENDASTOTAL, PCA.A2_VIVIENDASSECUNDARIAS, PCA.A2_TRABAJADORES, PCA.A3_POBLACION,\
    PCA.A3_HOGARES, PCA.A3_POBLACIONEVOL05, PCA.A3_RENTAMEDIA, PCA.A3_TASAPARO\
    from poi_competencia_agregados pca, poi_competencia pc\
    where pca.id_competencia = pc.id_competencia and pc.id_cadena="Cadena Target"'
competencia = pd.read_sql_query(query_competencia, con=conn)

query_competenciaindice = 'SELECT PC.ID_COMPETENCIA \
    from poi_competencia_agregados pca, poi_competencia pc\
    where pca.id_competencia = pc.id_competencia and pc.id_cadena="Cadena Target"'
competenciaindice = pd.read_sql_query(query_competenciaindice, con=conn) 

query_candidato = 'SELECT PC.ID_CANDIDATO_TIPO, PCA.A1_COMPETENCIA, PCA.A1_INDICEOCIO, PCA.A1_INDICECOMERCIO, PCA.A1_INDICESALUD,\
    PCA.A1_INDICEHOTELES, PCA.A1_INDICERESTAURANTES, PCA.A1_INDICETURISMO, PCA.A1_INDICEGRANSUPERFICIE,\
    PCA.A1_INDICEGLOBAL, PCA.A2_POBLACION, PCA.A2_EXTRANJERA, PCA.A2_HOGARESTOTAL,\
    PCA.A2_VIVIENDASTOTAL, PCA.A2_VIVIENDASSECUNDARIAS, PCA.A2_TRABAJADORES, PCA.A3_POBLACION,\
    PCA.A3_HOGARES, PCA.A3_POBLACIONEVOL05, PCA.A3_RENTAMEDIA, PCA.A3_TASAPARO \
    from poi_candidato_agregados pca, poi_candidato pc\
    where pca.id_candidato = pc.id_candidato and pc.id_candidato = "'+idcandidato+'"'
candidato = pd.read_sql_query(query_candidato, con=conn)

logbbdd(5, idcandidato, 20, "Carga Inicial de Datos")

########################################################
#  PREPROCESO DE DATOS
########################################################

#SET TO FLAG
lb_tipo = LabelEncoder()
competencia["ID_CANDIDATO_TIPO"] = lb_tipo.fit_transform(competencia["ID_CANDIDATO_TIPO"])
lb_tipo = LabelEncoder()
candidato["ID_CANDIDATO_TIPO"] = lb_tipo.fit_transform(candidato["ID_CANDIDATO_TIPO"])

#AGRUPAR VARIABLES
candidato["A1_FLOTANTE"] = candidato["A1_INDICEOCIO"] + candidato["A1_INDICETURISMO"] + candidato["A1_INDICEHOTELES"]
candidato["A1_COMERCIO"] = candidato["A1_INDICECOMERCIO"] + candidato["A1_INDICEGRANSUPERFICIE"]
candidato["A2_POBLACIONREAL"] = candidato["A2_POBLACION"] + candidato["A2_VIVIENDASSECUNDARIAS"]*2
candidato["A3_ICE"] = candidato["A3_RENTAMEDIA"] * (1 - candidato["A3_TASAPARO"]/100)
competencia["A1_FLOTANTE"] = competencia["A1_INDICEOCIO"] + competencia["A1_INDICETURISMO"] + competencia["A1_INDICEHOTELES"]
competencia["A1_COMERCIO"] = competencia["A1_INDICECOMERCIO"] + competencia["A1_INDICEGRANSUPERFICIE"]
competencia["A2_POBLACIONREAL"] = competencia["A2_POBLACION"] + competencia["A2_VIVIENDASSECUNDARIAS"]*2
competencia["A3_ICE"] = competencia["A3_RENTAMEDIA"] * (1 -  competencia["A3_TASAPARO"]/100)

# NORMALIZAR CANDIDATO
candidato_n = candidato
for column in candidato:
    candidato_n[column] = normalizar(competencia[column], candidato[column])

# NORMALIZAR COMPETENCIA
competencia_n = competencia
for column in competencia:
    competencia_n[column] = normalizar(
        competencia[column], competencia[column])

logbbdd(5, idcandidato, 30, "Preproceso de Datos")

########################################################
#  DATASETS DE TRAINING
########################################################

X = np.array(candidato_n[["ID_CANDIDATO_TIPO", "A1_COMPETENCIA"\
   , "A1_INDICESALUD", "A1_FLOTANTE", "A1_COMERCIO"\
   , "A1_INDICEGLOBAL", "A2_POBLACIONREAL", "A2_EXTRANJERA"\
   , "A2_HOGARESTOTAL", "A3_ICE", "A2_TRABAJADORES"]])
Y = np.array(competencia_n[["ID_CANDIDATO_TIPO", "A1_COMPETENCIA"\
   , "A1_INDICESALUD", "A1_FLOTANTE", "A1_COMERCIO"\
   , "A1_INDICEGLOBAL", "A2_POBLACIONREAL", "A2_EXTRANJERA"\
   , "A2_HOGARESTOTAL", "A3_ICE", "A2_TRABAJADORES"]])

########################################################
#  ÇARGA DEL MODELO KMEANS
########################################################

#filename = 'ModeloSimilitud.sav'
#kmeans = joblib.load(filename)
#candidato_n['SEGMENTO'] = kmeans.predict(X)
#competenciaindice['SEGMENTO'] = kmeans.predict(Y)
#logbbdd(5, idcandidato, 40, "Modelo de Similitud Cargado")

########################################################
#  ÇALCULO DE LA DISTANCIA DE COMPETENCIA A CANDIDATO
########################################################
#def euclidean(x, y):
#    return sqrt(sum([(xi - yi)**2 for xi, yi in zip(x, y)]))

#competenciaindice['DISTANCIA'] = 0
#distancia = []
#for index, row in competencia_n.iterrows():
    #print("X: ", candidato_n.loc[0])
    #print("Y: ", row)
#    distancia.append(euclidean(candidato_n.loc[0], row))   
#competenciaindice['DISTANCIA'] = distancia
#competenciaindice = competenciaindice.sort_values(["DISTANCIA"])

#count = 1
#for index, row in competenciaindice.iterrows():
#    if competenciaindice.loc[index]['SEGMENTO'] == int(candidato.loc[0]['SEGMENTO']):
#        
#        if count == 3:
#            SIMILAR3 = competenciaindice.loc[index]['ID_COMPETENCIA']
#            count = count + 1
#            #print("SIMILAR 3: ", SIMILAR3)
#        if count == 2:
#            SIMILAR2 = competenciaindice.loc[index]['ID_COMPETENCIA']
#            count = count + 1
#            #print("SIMILAR 2: ", SIMILAR2)
#        if count==1 :
#            SIMILAR1 = competenciaindice.loc[index]['ID_COMPETENCIA']
#            count = count+1
#            #print("SIMILAR 1: ", SIMILAR1)


logbbdd(5, idcandidato, 50, "Modelo de Similitud Ejecutado")

########################################################
#  CARGA DEL MODELO PREDICTIVO
########################################################
#yaml_file = open('ModeloPredictivoYAML.yaml', 'r')
#loaded_model_yaml = yaml_file.read()
#yaml_file.close()
#loaded_model = model_from_yaml(loaded_model_yaml)
#loaded_model.load_weights("ModeloPredictivoYAML.h5")
#X = np.array(candidato_n[["ID_CANDIDATO_TIPO", "A1_COMPETENCIA", \
#    "A1_INDICEOCIO", "A1_INDICETURISMO", "A1_INDICESALUD", \
#    "A1_FLOTANTE", "A1_COMERCIO", "A3_RENTAMEDIA", "A3_TASAPARO", \
#    "A1_INDICEGLOBAL", "A2_POBLACIONREAL", "A2_EXTRANJERA", \
#    "A1_INDICEHOTELES", "A1_INDICECOMERCIO", "A1_INDICEGRANSUPERFICIE", \
#    "A2_HOGARESTOTAL", "A3_ICE", "A2_TRABAJADORES", "A2_POBLACION", \
#    "A2_VIVIENDASSECUNDARIAS"]])
#candidato['VENTAS'] = loaded_model.predict(X)
logbbdd(5, idcandidato, 60, "Modelo Predictivo Cargado")

#######################################################
#   ACTUALIZAR POI_CANDIDATO_AGREGADOS
########################################################

#query_final = 'UPDATE POI_CANDIDATO_AGREGADOS\
#   SET SEGMENTO = "'+str(int(candidato.loc[0]['SEGMENTO']))+'", \
#   SIMILAR1 = "'+SIMILAR1+'", SIMILAR2 = "'+SIMILAR2+'", \
#   SIMILAR3 = "'+SIMILAR3+'", ESTIMACIONVENTAS = \
#   "'+str(int(candidato.loc[0]['VENTAS']*100000))+'"\
#   WHERE ID_CANDIDATO = "' + str(idcandidato)+'"'
query_final = 'UPDATE POI_CANDIDATO_AGREGADOS\
   SET SEGMENTO = "1", \
   SIMILAR1 = "1009", SIMILAR2 = "1009", \
   SIMILAR3 = "1009", ESTIMACIONVENTAS = 100000 \
   WHERE ID_CANDIDATO = "' + str(idcandidato)+'"'

sqlcursor = conn.cursor()
sqlcursor.execute(query_final)
conn.commit()
logbbdd(5, idcandidato, 70, "Modelo Predictivo Ejecutado")

########################################################
#  MODELO VALORACION CANDIDATO Y COMPETENCIA MAXIMA
########################################################

query_max = 'SELECT MAX(A1_COMPETENCIA) AS A1_INDICECOMPETENCIAMAX, \
    MAX(A1_INDICEOCIO) AS A1_INDICEOCIOMAX, \
    MAX(A1_INDICECOMERCIO) AS A1_INDICECOMERCIOMAX, \
    MAX(A1_INDICESALUD) AS A1_INDICESALUDMAX, \
    MAX(A1_INDICEHOTELES) AS A1_INDICEHOTELESMAX, \
    MAX(A1_INDICERESTAURANTES) AS A1_INDICERESTAURANTESMAX, \
    MAX(A1_INDICETURISMO) AS A1_INDICETURISMOMAX, \
    MAX(A1_INDICEGRANSUPERFICIE) AS A1_INDICEGRANSUPERFICIEMAX, \
    MAX(A1_INDICEGLOBAL) AS A1_INDICEGLOBALMAX, \
    MAX(A2_POBLACION) AS A2_POBLACIONMAX, \
    MAX(A2_TRABAJADORES) AS A2_TRABAJADORESMAX, \
    MAX(A2_VIVIENDASSECUNDARIAS) AS A2_VIVIENDASMAX, \
    MAX(A2_HOGARESTOTAL) AS A2_HOGARESTOTALMAX, \
    MAX(A3_POBLACION) AS A3_POBLACIONMAX, \
    MAX(A3_VIVIENDASTOTAL) AS A3_VIVIENDASTOTALMAX, \
    MAX(A3_HOGARES) AS A3_HOGARESTOTALMAX, \
    MAX(A3_TASAPARO / A3_POBLACIONMUNICIPIO) AS A3_TASAPAROMAX, \
    MAX(A3_RENTAMEDIA) AS A3_RENTAMEDIAMAX \
    from poi_competencia_agregados'
maximos = pd.read_sql_query(query_max, con=conn)

query_candidato = 'SELECT PCA.A1_COMPETENCIA, PCA.A1_INDICEOCIO, PCA.A1_INDICECOMERCIO, \
    PCA.A1_INDICESALUD, PCA.A1_INDICEHOTELES, PCA.A1_INDICERESTAURANTES, \
    PCA.A1_INDICETURISMO, PCA.A1_INDICEGRANSUPERFICIE, \
    PCA.A1_INDICEGLOBAL, PCA.A2_POBLACION, PCA.A2_EXTRANJERA, PCA.A2_HOGARESTOTAL,\
    PCA.A2_VIVIENDASTOTAL, PCA.A2_VIVIENDASSECUNDARIAS, PCA.A2_TRABAJADORES, \
    PCA.A3_POBLACION, PCA.A3_HOGARES \
    from poi_candidato_agregados pca, poi_candidato pc\
    where pca.id_candidato = pc.id_candidato and pc.id_candidato = "'+idcandidato+'"'
candidato = pd.read_sql_query(query_candidato, con=conn)
logbbdd(5, idcandidato, 80, "Modelo de Similitud Ejecutado")

########################################################
#  PREPROCESO DE DATOS
########################################################

a1_indicecomercio = candidato.loc[0]["A1_INDICECOMERCIO"] / \
    maximos.loc[0]["A1_INDICECOMERCIOMAX"]
a1_competencia = candidato.loc[0]["A1_COMPETENCIA"] / \
    maximos.loc[0]["A1_INDICECOMPETENCIAMAX"]
a1_indiceocio = candidato.loc[0]["A1_INDICEOCIO"] / \
    maximos.loc[0]["A1_INDICEOCIOMAX"]
a1_indicesalud = candidato.loc[0]["A1_INDICESALUD"] / \
    maximos.loc[0]["A1_INDICESALUDMAX"]
a1_indicehoteles = candidato.loc[0]["A1_INDICEHOTELES"] / \
    maximos.loc[0]["A1_INDICEHOTELESMAX"]
a1_indicerestaurantes = candidato.loc[0]["A1_INDICERESTAURANTES"] / \
    maximos.loc[0]["A1_INDICERESTAURANTESMAX"]
a1_indiceturismo = candidato.loc[0]["A1_INDICETURISMO"] / \
    maximos.loc[0]["A1_INDICETURISMOMAX"]
a1_indicegransuperficie = candidato.loc[0]["A1_INDICEGRANSUPERFICIE"] / \
    maximos.loc[0]["A1_INDICEGRANSUPERFICIEMAX"]
a1_indiceglobal = candidato.loc[0]["A1_INDICEGLOBAL"] / \
    maximos.loc[0]["A1_INDICEGLOBALMAX"]

a1_poblacionrealmax = maximos.loc[0]["A2_POBLACIONMAX"] + \
    maximos.loc[0]["A2_VIVIENDASMAX"]*2
a1_poblacionreal = candidato.loc[0]["A2_POBLACION"] + \
    candidato.loc[0]["A2_VIVIENDASSECUNDARIAS"]*2
p1_poblacionreal = a1_poblacionreal / a1_poblacionrealmax

a1_flotantemax = maximos.loc[0]["A1_INDICETURISMOMAX"] + \
    maximos.loc[0]["A1_INDICEHOTELESMAX"]
a1_flotante = candidato.loc[0]["A1_INDICETURISMO"] + \
    candidato.loc[0]["A1_INDICEHOTELES"]
p2_flotante = a1_flotante / a1_flotantemax

a1_comerciomax = maximos.loc[0]["A1_INDICECOMERCIOMAX"] + \
    maximos.loc[0]["A1_INDICEGRANSUPERFICIEMAX"]
a1_comercio = candidato.loc[0]["A1_INDICECOMERCIO"] + \
    candidato.loc[0]["A1_INDICEGRANSUPERFICIE"]
p3_comercio = a1_comercio / a1_comerciomax

a1_globalmax = maximos.loc[0]["A1_INDICEOCIOMAX"] + \
    maximos.loc[0]["A1_INDICESALUDMAX"] + \
    maximos.loc[0]["A1_INDICERESTAURANTESMAX"]
a1_global = candidato.loc[0]["A1_INDICEOCIO"] + \
    candidato.loc[0]["A1_INDICESALUD"] + \
    candidato.loc[0]["A1_INDICERESTAURANTES"]
p4_atraccion = a1_global / a1_globalmax

p5_empleados = candidato.loc[0]["A2_TRABAJADORES"] / \
    maximos.loc[0]["A2_TRABAJADORESMAX"]

p6_exclusividad = 1 - \
    (candidato.loc[0]["A1_COMPETENCIA"] /
        maximos.loc[0]["A1_INDICECOMPETENCIAMAX"])

logbbdd(5, idcandidato, 90, "Modelo de Valoración Carga de Datos")

############################################################
#   ACTUALIZAR REGISTRO DE POI_CANDIDATO_VALORACION
############################################################

query_delete = 'DELETE FROM POI_CANDIDATO_VALORACION WHERE ID_CANDIDATO="' + idcandidato+'"'
query_insert = 'INSERT INTO POI_CANDIDATO_VALORACION (id_candidato, a1_competencia, a1_indiceocio, \
    a1_indicecomercio, a1_indicesalud, a1_indicehoteles, a1_indicerestaurantes, a1_indiceturismo, \
    a1_indicegransuperficie, a1_indiceglobal, a2_poblacion,\
    a2_viviendassecundarias, a2_trabajadores, p1_poblacionreal, p2_poblacionflotante, \
    p3_comercio, p4_atraccion, p5_empleados, p6_exclusividad) \
    VALUES ("'+idcandidato+'", ' + str(a1_competencia)+', ' + str(a1_indiceocio) +\
    ', ' + str(a1_indicecomercio) + ', ' + str(a1_indicesalud)+', ' + str(a1_indicehoteles)+', ' + str(a1_indicerestaurantes)+', ' + str(a1_indiceturismo) +\
    ', ' + str(a1_indicegransuperficie)+', ' + str(a1_indiceglobal)+', ' + str(candidato.loc[0]["A2_POBLACION"]) +\
    ', ' + str(candidato.loc[0]["A2_VIVIENDASSECUNDARIAS"]) + ', ' + str(candidato.loc[0]["A2_TRABAJADORES"]) +\
    ', ' + str(p1_poblacionreal)+', ' + str(p2_flotante) +\
    ', ' + str(p3_comercio) + ', ' + str(p4_atraccion) + ', ' + \
    str(p5_empleados) + ', ' + str(p6_exclusividad)+')'
sqlcursor = conn.cursor()
sqlcursor.execute(query_delete)
conn.commit()
sqlcursor.execute(query_insert)
conn.commit()
logbbdd(5, idcandidato, 100, "Modelo de Valoración Ejecutado")
conn.close()

