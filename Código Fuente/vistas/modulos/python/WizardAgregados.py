# -*- coding: utf-8 -*-

import pyproj
import sys
import pymysql
import pandas as pd
geod = pyproj.Geod(ellps="WGS84")

# DECLARACION DE VARIABLES
id_candidato = sys.argv[1];
a1_distance = 500
a2_distance = 1000
a3_distance = 3000

# CADENA DE CONEXION A BASE DE DATOS
conn = pymysql.connect(
    host="localhost", port=3306, user="root",
    passwd="", db="pfg"
)

########################################################
#  LOG DE EJECUCION EN BBDD
########################################################
id_proceso = 1
sqlcursor = conn.cursor()
sqlcursor.execute(
    "DELETE FROM PROCESOS_PYTHON WHERE ID_PROCESO =%s and ID_CANDIDATO=%s", (id_proceso, id_candidato))
conn.commit()


def logbbdd(id_proceso, id_candidato, porcentaje, mensaje):

    sqlcursor = conn.cursor()
    sqlcursor.execute("INSERT INTO PROCESOS_PYTHON(ID_PROCESO, ID_CANDIDATO, PROGRESO_PORCENTAJE, PROGRESO_DESCRIPCION) \
                VALUES(%s, %s, %s, %s)", (id_proceso, id_candidato, porcentaje, mensaje))

    conn.commit()
    return


logbbdd(1, id_candidato, 10, "Importación de Librerias")
########################################################
#   INFORMACION DEL PUNTO CANDIDATO
########################################################

distancia_minima = 100000
punto_seccion = "2800101001"
punto_indice = 0
query_candidato = "SELECT c.id_candidato, c.latitud, c.longitud, c.id_cadena, cc.peso  \
    FROM poi_candidato c, sp_competencia_cadena cc \
    WHERE c.id_cadena = cc.id_competencia_cadena "
candidato = pd.read_sql_query(query_candidato, con=conn)

for i in range(0, len(candidato)):
    if id_candidato == candidato.loc[i]['id_candidato']:
        punto_indice = i
        break
punto_lat = candidato.loc[punto_indice]['latitud']
punto_lon = candidato.loc[punto_indice]['longitud']

# CALCULO DE LA SECCION CENSAL Y EL MUNICIPIO
query_sscc2017 = 'SELECT S.ID_SSCC, S.LATGWS84, S.LONGWS84, \
    CONCAT(S.ID_PROVINCIA, S.ID_MUNICIPIO3) AS MUNICIPIO \
    FROM SP_SSCC S WHERE S.ID_PROVINCIA = "28" AND S.ANYO = 2017'
secciones2017 = pd.read_sql_query(query_sscc2017, conn)
for i in range(0, len(secciones2017)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, secciones2017.loc[i]['LONGWS84'], secciones2017.loc[i]['LATGWS84'])
    secciones2017.at[i, 'DISTANCIA'] = distance
    if distance < distancia_minima:
        distancia_minima = distance
        punto_seccion = secciones2017.loc[i]['ID_SSCC']
        punto_municipio = secciones2017.loc[i]['MUNICIPIO']

########################################################
#   CALCULO DEL AREA 1
########################################################

query_atractorarea = "SELECT PA.ID_ATRACTOR, PA.LATITUD, PA.LONGITUD \
    FROM POI_ATRACTOR PA"
atractorarea = pd.read_sql_query(query_atractorarea, conn)
query_deletearea = 'DELETE FROM POI_CANDIDATO_A1 WHERE ID_CANDIDATO="' + id_candidato + '"'
sqlcursor = conn.cursor()
sqlcursor.execute(query_deletearea)
conn.commit()

for i in range(0, len(atractorarea)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, atractorarea.loc[i]['LONGITUD'], atractorarea.loc[i]['LATITUD'])
    if distance < a1_distance:
        query_inserta = 'INSERT INTO POI_CANDIDATO_A1 (id_candidato, id_atractor, distancia )\
            VALUES ("'+id_candidato+'", '+str(atractorarea.loc[i]['ID_ATRACTOR']) + ', ' + str(distance) + ')'
        sqlcursor.execute(query_inserta)
        conn.commit()
logbbdd(5, id_candidato, 20, "Area A1 Creada")

########################################################
#   CALCULO DEL AREA 2 Y AREA 3
########################################################

query_ssccarea = 'SELECT S.ID_SSCC, S.LATGWS84, S.LONGWS84 \
     FROM SP_SSCC S \
     WHERE S.ID_PROVINCIA = "28" AND S.ANYO =2017'
seccionesarea = pd.read_sql_query(query_ssccarea, conn)
query_deletearea = 'DELETE FROM POI_CANDIDATO_AREA WHERE ID_CANDIDATO="' + id_candidato+'"'
sqlcursor = conn.cursor()
sqlcursor.execute(query_deletearea)
conn.commit()

for i in range(0, len(seccionesarea)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, seccionesarea.loc[i]['LONGWS84'], seccionesarea.loc[i]['LATGWS84'])
    if distance < a2_distance:
        query_inserta = 'INSERT INTO POI_CANDIDATO_AREA (id_candidato, id_sscc, anyo, area )\
            VALUES ("'+id_candidato+'", '+str(seccionesarea.loc[i]['ID_SSCC']) + ', 2017, "A2")'
        sqlcursor.execute(query_inserta)
        conn.commit()
    elif distance < a3_distance:
        query_insertb = 'INSERT INTO POI_CANDIDATO_AREA (id_candidato, id_sscc, anyo, area )\
            VALUES ("'+id_candidato+'", '+str(seccionesarea.loc[i]['ID_SSCC']) + ', 2017, "A3")'
        sqlcursor.execute(query_insertb)
        conn.commit()
logbbdd(1, id_candidato, 30, "Areas A2 y A3 Creadas")

########################################################
#   CALCULO DEL INDICE DE COMPETENCIA
########################################################
a1_competencia = 0
query_compe = "SELECT c.id_competencia, c.latitud, c.longitud, c.id_cadena, cc.peso  \
    FROM poi_competencia c, sp_competencia_cadena cc \
    WHERE c.id_cadena = cc.id_competencia_cadena "
competidor = pd.read_sql_query(query_compe, con=conn)
for i in range(0, len(competidor)):
    angle1, angle2, distance = geod.inv(punto_lon, punto_lat, competidor.loc[i]['longitud'],
                                        competidor.loc[i]['latitud'])
    if distance < a1_distance:
        a1_competencia = a1_competencia + competidor.loc[i]['peso']

logbbdd(1, id_candidato, 40, "Indice De Competencia Calculado")

########################################################
#   CALCULO DE LOS INDICES DE ATRACCION
########################################################
a1_ocio = 0
a1_comercio = 0
a1_salud = 0
a1_hoteles = 0
a1_global = 0
a1_restaurantes = 0
a1_turismo = 0
a1_gran = 0
query_atractor = "SELECT PA.ID_ATRACTOR, PA.LATITUD, PA.LONGITUD, PA.REVIEWS,\
    A.ATRACTOR_NOMBRE, AF.ID_ATRACTOR_FAMILIA, \
    AF.ATRACTOR_FAMILIA_NOMBRE, A.COEFICIENTE, A.PESOMIN, A.PESOMAX \
    FROM POI_ATRACTOR PA, SP_ATRACTOR A, SP_ATRACTOR_FAMILIA AF \
    WHERE PA.ID_ATRACTOR_ACTIVIDAD = A.ID_ATRACTOR_ACTIVIDAD \
    AND A.ID_ATRACTOR_FAMILIA = AF.ID_ATRACTOR_FAMILIA"

atractor = pd.read_sql_query(query_atractor, conn)
for i in range(0, len(atractor)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, atractor.loc[i]['LONGITUD'], atractor.loc[i]['LATITUD'])
    if distance < a1_distance:
        if atractor.loc[i]['REVIEWS'] < atractor.loc[i]['PESOMIN']:
            valor = 1
        elif atractor.loc[i]['REVIEWS'] > atractor.loc[i]['PESOMAX']:
            valor = atractor.loc[i]['COEFICIENTE']
        else:
            valor = atractor.loc[i]['COEFICIENTE'] * (atractor.loc[i]['REVIEWS']
                                                      - atractor.loc[i]['PESOMIN'])/(atractor.loc[i]['PESOMAX']-atractor.loc[i]['PESOMIN'])
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Ocio":
            a1_ocio = a1_ocio + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Comercio":
            a1_comercio = a1_comercio + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Salud":
            a1_salud = a1_salud + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Hoteles":
            a1_hoteles = a1_hoteles + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Restauracion y Bares":
            a1_restaurantes = a1_restaurantes + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Turismo":
            a1_turismo = a1_turismo + valor
        if atractor.loc[i]['ID_ATRACTOR_FAMILIA'] == "Gran Superficie":
            a1_gran = a1_gran + valor
        a1_global = a1_global + valor

a1_ocio = round(a1_ocio, 2)
a1_comercio = round(a1_comercio, 2)
a1_salud = round(a1_salud, 2)
a1_hoteles = round(a1_hoteles, 2)
a1_restaurantes = round(a1_restaurantes, 2)
a1_turismo = round(a1_turismo, 2)
a1_gran = round(a1_gran, 2)
a1_global = round(a1_global, 2)

logbbdd(1, id_candidato, 50, "Indices de Atracción Calculados")

############################################################
#   CALCULO DE LAS VARIABLES AGREGADAS PARA SECCIONADO 2017
############################################################

a2_total = 0
a2_menores = 0
a2_jovenes = 0
a2_adultos = 0
a2_mayores = 0
a2_extranjera = 0
a2_espanya = 0
a3_total = 0
a3_menores = 0
a3_jovenes = 0
a3_adultos = 0
a3_mayores = 0
a3_extranjera = 0
a3_espanya = 0

query_sscc2017 = 'SELECT S.ID_SSCC, S.LATGWS84, S.LONGWS84 , P.TOTAL, (P.EDAD0004+P.EDAD0509+P.EDAD1014) AS MENORES, \
    (P.EDAD1519+P.EDAD2024+P.EDAD2529) AS JOVENES, \
    (P.EDAD3034+P.EDAD3539+P.EDAD4044+P.EDAD4549+P.EDAD5054+P.EDAD5559) AS ADULTOS, \
    (P.TOTAL -(P.EDAD0004+P.EDAD0509+P.EDAD1014)-(P.EDAD1519+P.EDAD2024+P.EDAD2529)- \
     (P.EDAD3034+P.EDAD3539+P.EDAD4044+P.EDAD4549+P.EDAD5054+P.EDAD5559)) AS MAYORES, \
     O.EXTRANJERA, O.ESPANYA \
     FROM SP_SSCC S, SSCC_POB01 P, SSCC_POB02 O \
     WHERE S.ID_SSCC = P.ID_SSCC \
     AND S.ID_SSCC = O.ID_SSCC\
     AND S.ANYO = P.ANYO \
     AND S.ANYO = O.ANYO \
     AND S.ID_PROVINCIA = "28" AND P.ANYO ="2017" AND P.SEXO = "AMBOS" AND O.SEXO = "AMBOS"'
secciones2017 = pd.read_sql_query(query_sscc2017, conn)
for i in range(0, len(secciones2017)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, secciones2017.loc[i]['LONGWS84'], secciones2017.loc[i]['LATGWS84'])
    secciones2017.at[i, 'DISTANCIA'] = distance
    if distance < a2_distance:
        a2_total = a2_total + secciones2017.loc[i]['TOTAL']
        a2_menores = a2_menores + secciones2017.loc[i]['MENORES']
        a2_jovenes = a2_jovenes + secciones2017.loc[i]['JOVENES']
        a2_adultos = a2_adultos + secciones2017.loc[i]['ADULTOS']
        a2_mayores = a2_mayores + secciones2017.loc[i]['MAYORES']
        a2_extranjera = a2_extranjera + secciones2017.loc[i]['EXTRANJERA']
        a2_espanya = a2_espanya + secciones2017.loc[i]['ESPANYA']
    if distance < a3_distance:
        a3_total = a3_total + secciones2017.loc[i]['TOTAL']
        a3_menores = a3_menores + secciones2017.loc[i]['MENORES']
        a3_jovenes = a3_jovenes + secciones2017.loc[i]['JOVENES']
        a3_adultos = a3_adultos + secciones2017.loc[i]['ADULTOS']
        a3_mayores = a3_mayores + secciones2017.loc[i]['MAYORES']
        a3_extranjera = a3_extranjera + secciones2017.loc[i]['EXTRANJERA']
        a3_espanya = a3_espanya + secciones2017.loc[i]['ESPANYA']

logbbdd(1, id_candidato, 60, "Calculo de Variables Agregada por Area")

############################################################
#   CALCULO DE LAS VARIABLES AGREGADAS PARA SECCIONADO 2011
############################################################
a2_hogares = 0
a2_sinhijos = 0
a2_conhijos = 0
a2_viviendas = 0
a2_principales = 0
a2_secundarias = 0
a3_hogares = 0
a3_sinhijos = 0
a3_conhijos = 0
a3_viviendas = 0
a3_principales = 0
a3_secundarias = 0

query_sscc2011 = 'SELECT S.ID_SSCC, S.LATGWS84, S.LONGWS84 , H.HOGARESTOTAL, (H.HOGARES01+H.HOGARES02) AS SINHIJOS, \
     (H.HOGARESTOTAL-H.HOGARES01-H.HOGARES02) AS CONHIJOS, \
	  V.TOTALVIVIENDAS, V.VIVIENDASPRINCIPALES, V.VIVIENDASSECUNDARIAS \
     FROM SP_SSCC S, SSCC_HOGARES H, SSCC_VIVIENDAS V  \
     WHERE S.ID_SSCC = H.ID_SSCC \
     AND S.ID_SSCC = V.ID_SSCC \
     AND S.ANYO = H.ANYO \
     AND S.ANYO = V.ANYO \
     AND S.ID_PROVINCIA = "28" AND S.ANYO =2011'
secciones2011 = pd.read_sql_query(query_sscc2011, conn)
for i in range(0, len(secciones2011)):
    angle1, angle2, distance = geod.inv(
        punto_lon, punto_lat, secciones2011.loc[i]['LONGWS84'], secciones2011.loc[i]['LATGWS84'])
    secciones2011.at[i, 'DISTANCIA'] = distance
    if distance < a2_distance:
        a2_hogares = a2_hogares + secciones2011.loc[i]['HOGARESTOTAL']
        a2_sinhijos = a2_sinhijos + secciones2011.loc[i]['SINHIJOS']
        a2_conhijos = a2_conhijos + secciones2011.loc[i]['CONHIJOS']
        a2_viviendas = a2_viviendas + secciones2011.loc[i]['TOTALVIVIENDAS']
        a2_principales = a2_principales + \
            secciones2011.loc[i]['VIVIENDASPRINCIPALES']
        a2_secundarias = a2_secundarias + \
            secciones2011.loc[i]['VIVIENDASSECUNDARIAS']
    if distance < a3_distance:
        a3_hogares = a3_hogares + secciones2011.loc[i]['HOGARESTOTAL']
        a3_sinhijos = a3_sinhijos + secciones2011.loc[i]['SINHIJOS']
        a3_conhijos = a3_conhijos + secciones2011.loc[i]['CONHIJOS']
        a3_viviendas = a3_viviendas + secciones2011.loc[i]['TOTALVIVIENDAS']
        a3_principales = a3_principales + \
            secciones2011.loc[i]['VIVIENDASPRINCIPALES']
        a3_secundarias = a3_secundarias + \
            secciones2011.loc[i]['VIVIENDASSECUNDARIAS']

logbbdd(1, id_candidato, 70, "Calculo de Variables Agregadsa por Seccion Censal")


############################################################
#   CALCULO DE LAS VARIABLES AGREGADAS PARA MUNICIPIO 2017
############################################################

query_munievol = 'SELECT M.ID_MUNICIPIO, M.EVOLUCION05, M.EVOLUCION10, M.EVOLUCION15, M.ANYO2017 \
     FROM MUNI_POB_EVOL M \
     WHERE M.ID_MUNICIPIO = "'+punto_municipio+'"'
munievol = pd.read_sql_query(query_munievol, conn)
query_muniparo = 'SELECT P.ID_MUNICIPIO, P.PAROTOTAL\
     FROM MUNI_PARO P \
     WHERE P.ID_MUNICIPIO = "'+punto_municipio+'"'
muniparo = pd.read_sql_query(query_muniparo, conn)
query_munirenta = 'SELECT R.ID_MUNICIPIO, R.RENTABRUTAMEDIA \
     FROM MUNI_RENTA R \
     WHERE R.ID_MUNICIPIO = "'+punto_municipio+'"'
munirenta = pd.read_sql_query(query_munirenta, conn)
a3_tasaparo = muniparo.loc[0]['PAROTOTAL']
a3_rentamedia = round(munirenta.loc[0]['RENTABRUTAMEDIA'], 2)
a3_poblacionmunicipio = round(munievol.loc[0]['ANYO2017'], 0)
a3_tasaparo = round(100*a3_tasaparo/a3_poblacionmunicipio, 2)
a3_evol05 = round(munievol.loc[0]['EVOLUCION05'], 2)
a3_evol10 = round(munievol.loc[0]['EVOLUCION10'], 2)
a3_evol15 = round(munievol.loc[0]['EVOLUCION15'], 2)

logbbdd(1, id_candidato, 80, "Calculo de Variables Agregadsa por Municipìo")

############################################################
#   CALCULO DE LAS VARIABLES AGREGADAS DE EMPRESAS
############################################################

query_empresas = 'SELECT CASE WHEN  SUM(EE.NUMMEDIO) IS NULL THEN 0\
    ELSE SUM(EE.NUMMEDIO) \
    END AS EMPLEADOS \
     FROM POI_EMPRESAS PE, SP_EMPRESA_EMPLEADOS EE, POI_COMPETENCIA_AREA PCA\
     WHERE PCA.ID_SSCC = PE.ID_SSCC AND \
     PE.ID_NUMEMPLEADOS = EE.ID_NUMEMPLEADOS AND \
     PCA.ID_COMPETENCIA = "' + id_candidato + '" AND PCA.ANYO = 2017 AND AREA="A2"'
empresas = pd.read_sql_query(query_empresas, conn)
a2_trabajadores = empresas.loc[0]['EMPLEADOS']
logbbdd(1, id_candidato, 90, "Calculo de Variables Agregadas de Empresas")

############################################################
#   ACTUALIZAR REGISTRO DE POI_CANDIDATO_AGREGADOS
############################################################

query_delete = 'DELETE FROM POI_CANDIDATO_AGREGADOS WHERE ID_CANDIDATO="' + id_candidato+'"'
query_insert = 'INSERT INTO POI_CANDIDATO_AGREGADOS (id_candidato, a1_competencia, a1_indiceocio, \
    a1_indicecomercio, a1_indicesalud, a1_indicehoteles, a1_indicerestaurantes, a1_indiceturismo, \
    a1_indicegransuperficie, a1_indiceglobal, a2_poblacion, a2_menores, a2_jovenes, a2_adultos, a2_mayores,\
    a2_extranjera, a2_espanya, a2_hogarestotal, a2_sinhijos, a2_conhijos, a2_viviendastotal, a2_viviendasprincipales,\
    a2_viviendassecundarias, a2_trabajadores, a3_poblacion, a3_hogares, a3_extranjera, a3_viviendastotal, \
    a3_poblacionevol05, a3_poblacionevol10, a3_poblacionevol15, a3_rentamedia, a3_tasaparo, a3_poblacionmunicipio )\
    VALUES ("'+id_candidato+'", ' + str(a1_competencia)+', ' + str(a1_ocio)+', ' + str(a1_comercio) +\
    ', ' + str(a1_salud)+', ' + str(a1_hoteles)+', ' + str(a1_restaurantes)+', ' + str(a1_turismo) +\
    ', ' + str(a1_gran)+', ' + str(a1_global)+', ' + str(a2_total)+', ' + str(a2_menores)+', ' + str(a2_jovenes) +\
    ', ' + str(a2_adultos)+', ' + str(a2_mayores)+', ' + str(a2_extranjera)+', ' + str(a2_espanya) +\
    ', ' + str(a2_hogares)+', ' + str(a2_sinhijos)+', ' + str(a2_conhijos)+', ' + str(a2_viviendas) +\
    ', ' + str(a2_principales)+', ' + str(a2_secundarias)+', ' + str(a2_trabajadores)+', ' + str(a3_total) +\
    ', ' + str(a3_hogares)+', ' + str(a3_extranjera)+', ' + str(a3_viviendas) +\
    ', ' + str(a3_evol05)+', ' + str(a3_evol10)+', ' + str(a3_evol15) +\
    ', ' + str(a3_rentamedia)+', ' + str(a3_tasaparo) + \
    ', ' + str(a3_poblacionmunicipio)+')'
sqlcursor = conn.cursor()
sqlcursor.execute(query_delete)
conn.commit()
sqlcursor.execute(query_insert)


############################################################
#   ACTUALIZAR REGISTRO DE POI_CANDIDATO_AGREGADOS
############################################################
print("ID Candidato: ", id_candidato)
query_estado = "UPDATE POI_CANDIDATO SET id_estado = '2' WHERE id_candidato = '"+id_candidato+"'"
print(query_estado)
sqlcursor = conn.cursor()
sqlcursor.execute(query_estado)
conn.commit()
logbbdd(1, id_candidato, 100, "Proceso Finalizado")
conn.close()
