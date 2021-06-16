import pytesseract
import difflib
import itertools
import mysql.connector
import timeit
# import mysql.connector
import sys
import os
from wand.image import Image

import re
from PIL import ImageFilter
import json


import cv2
import numpy as np
# import os
# import pytesseract

import pathlib

start = timeit.default_timer()
db = mysql.connector.connect(
            host="localhost",user="root",passwd="",db="si_pp" )

def ocr_revisi(img_real_name):
    image = cv2.imread(img_real_name)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    thresh = cv2.threshold(gray, 255, 255,cv2.THRESH_TRUNC)[1]

    # Remove horizontal lines
    horizontal_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (50,1))
    detect_horizontal = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, horizontal_kernel, iterations=2)
    cnts = cv2.findContours(detect_horizontal, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    cnts = cnts[0] if len(cnts) == 2 else cnts[1]
    for c in cnts:
        cv2.drawContours(thresh, [c], -1, (0,0,0), 2)

    # Remove vertical lines
    vertical_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1,15))
    detect_vertical = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, vertical_kernel, iterations=2)
    cnts = cv2.findContours(detect_vertical, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    cnts = cnts[0] if len(cnts) == 2 else cnts[1]
    for c in cnts:
        cv2.drawContours(thresh, [c], -1, (0,0,0), 3)

    # Dilate to connect text and remove dots
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (10,1))
    dilate = cv2.dilate(thresh, kernel, iterations=2)
    cnts = cv2.findContours(dilate, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    cnts = cnts[0] if len(cnts) == 2 else cnts[1]
    for c in cnts:
        area = cv2.contourArea(c)
        if area < 500:
            cv2.drawContours(dilate, [c], -1, (0,0,0), -1)

    # Bitwise-and to reconstruct image
    result = cv2.bitwise_and(image, image, mask=dilate)
    result[dilate==0] = (255,255,255)
     # OCR
    data = pytesseract.image_to_string(result, lang='eng',config='--psm 6')
    return data

def cari_indeks_dosen(dosen2) :
    dosen_raw3=[]
    se = db.cursor()
    se.execute("SELECT nama FROM dosen" )
    rs = se.fetchall()
    daftar_nama_dosen = list(itertools.chain(*rs))
    dosen_raw1=[];dosen_raw2=[]
    for i in range(len(daftar_nama_dosen)):
        a=daftar_nama_dosen[i].replace("S.T.","").replace("S.T.","").replace("M.S.","").replace("Prof.","").replace("Dr.","").replace("S.Kom.","").replace(" M.T.","").replace("IPM.","")
        b=a.replace(" M.Eng.","").replace("Ir.","").replace("M.Eng.Sc","").replace("Env.Eng","").replace("Ph.D.","").replace("M.Sc.","").replace(" B.Eng.","").replace(",","")
        c=b.replace(" M.Si.","").replace("M.Sc","").replace("M.M.","").replace("Ing.","").replace("M.I.T.","").replace("rer.oec.","").replace("M.Sp.","").replace("Ph.D","")
        c=c.replace("Dipl.","").replace("S.Si.","").replace("M.Ing.","").replace("MPS","").replace("Eng.","").replace("I.","").replace("T.","").replace("Dipl.Ing.","")
        c=c.replace("CES","").replace("DEA","").replace("L.M.","").replace("MAsc.","").replace("M.T","").replace("Dra.","").replace("Dipl.GE","").replace("S.T","")
        dosen_raw1.append(c)
    for i in range(len(dosen2)):
        a=dosen2[i].replace("S.T.","").replace("$.T.","").replace("ST.","").replace("MT.","")
        c=a.replace("M.Sc.","").replace("Dr","").replace("Eng","").replace(",","").replace(".","")
        c=c.replace("M.S.","").replace("Prof.","").replace("Dr.","").replace("S.Kom.","").replace(" M.T.","").replace("IPM.","")
        c=c.replace(" M.Eng.","").replace("Ir.","").replace("M.Eng.Sc","").replace("Env.Eng","").replace("Ph.D.","").replace("M.Sc.","").replace(" B.Eng.","").replace(",","")
        c=c.replace(" M.Si.","").replace("M.Sc","").replace("M.M.","").replace("Ing.","").replace("M.I.T.","").replace("rer.oec.","").replace("M.Sp.","").replace("Ph.D","")
        c=c.replace("Dipl.","").replace("S.Si.","").replace("M.Ing.","").replace("MPS","").replace("Eng.","").replace("I.","").replace("T.","").replace("Dipl.Ing.","")
        c=c.replace("CES","").replace("DEA","").replace("L.M.","").replace("MAsc.","").replace("M.T","").replace("Dra.","").replace("Dipl.GE","").replace("S.T","")
        dosen_raw2.append(c)
    for i in range(len(dosen_raw2)):
        lo=difflib.get_close_matches(dosen_raw2[i], dosen_raw1, 1)
        if len(lo)==1:
            dosen_raw3.append(lo[0])
        else:
            continue
    i=0
    
    indeks_dosen=[]
    for po in range(len(dosen_raw1)):
        for i in range(len(dosen_raw3)):
            if dosen_raw3[i] in dosen_raw1[po] :
                
                indeks_dosen.append(po)
    return indeks_dosen

def ambil_nip_dosen(indeks_dosen) :
    nip_dosen_final=[]
    se = db.cursor()
    se.execute("SELECT nip FROM dosen" )
    rs = se.fetchall()
    nip = list(itertools.chain(*rs))
    
    for i in range(len(indeks_dosen)):
        nip_dosen_final.append(nip[indeks_dosen[i]])
        
    return nip_dosen_final
    
def ambil_nama_dosen(indeks_dosen) :
    nama_dosen_final=[]
    se = db.cursor()
    se.execute("SELECT nama FROM dosen" )
    rs = se.fetchall()
    daftar_nama_dosen = list(itertools.chain(*rs))
        
    for i in range(len(indeks_dosen)):
        nama_dosen_final.append(daftar_nama_dosen[indeks_dosen[i]])
    
    return nama_dosen_final

parent_dir = pathlib.Path(__file__).parent.absolute()
add_ons_file = str(parent_dir)+str("\ ").replace(" ","")
khusus_filename = str(sys.argv[1])

outfile_json = add_ons_file+"hasil_abdi.json"
outfile = add_ons_file+"result_abdi.txt"
f = open(outfile, "w")
f.write("")
f.close()


with(Image(filename=khusus_filename, resolution=120)) as source:
    filename=khusus_filename
    global images
    images = source.sequence
    pages = len(images)
    if pages < 4 :
        page=len(images)
    if pages >= 4 :
        page=4
    for i in range(page):
        n = i + 1
        newfilename = filename[:-4] + str(n) + '.jpeg'
        Image(images[i]).save(filename=newfilename)

        # print(ocr_revisi(newfilename))

        # Creating a text file to write the output

        # Open the file in append mode so that
        # All contents of all images are added to the same file
        f = open(outfile, "a")

        pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe'
        # pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe'
        #
        # Recognize the text as string in image using pytesserct

        #text = text.replace('-\n', '')
        hasil_ocr_revisi = str(ocr_revisi(newfilename)).replace('-\n', '').encode('utf-8').decode('ascii', 'ignore')
        # print(hasil_ocr_revisi)
        f.write(str(hasil_ocr_revisi))

    for i in range(page):
        n = i + 1
        newfilename = filename[:-4] + str(n) + '.jpeg'
        os.remove(newfilename)

f.close()

################################################################################
################################################################################

def removeF(data):

    if data[0] == ".":
        data = data[1:]
    if data[0].islower():
        data = data[1:]
    return data

text_as_string = open(outfile, 'r').read()
text_arrays = text_as_string.split('\n')
print(text_arrays)
index_ketua_pelaksana = 0
start_judul_index  = 0

###PENGAMBILAN JUDUL
###PROSES MENCARI INDEKS YANG MENANDAKAN BATAS AWAL AMBIL JUDUL 
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Judul' in text_array:
        start_judul_index = index
        break
    index+=1

index = start_judul_index
result_judul = ""

for x in range(10):
    if 'Ketua Pelaksana' in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index
        break
    if 'Nama Mitra' in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index+1
        break
    if 'Ketua Tim'in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index+1
        break
    if 'Ketua'in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index+1
        break
    if 'Luaran Pengabdian' in text_arrays[index]:
            start_judul_index = index
            index_ketua_pelaksana = index+1
            break
    result_judul += text_arrays[index]
    result_judul += ' '
    index+=1
result_judul = result_judul.replace('Judul','').replace('Pengabdian :','')
result_judul = result_judul.replace('pengabdian :','').replace('Pengabdian','')

###########################################
start_abstrak_index  = 0
index = 0
#is_daftar_isi = True

#n_ringkasan = 0
#for text_array in text_arrays:
#   if 'RINGKASAN' in text_array:
#        n_ringkasan +=1

#if n_ringkasan == 1:
#    is_daftar_isi = False

# print("n_ringkasan")
# print(n_ringkasan)
for text_array in text_arrays:
    # print(text_array)
    if 'RINGKASAN' in text_array:
        start_abstrak_index = index
        break
    if 'ABSTRAK' in text_array:
        start_abstrak_index = index
        break

    if index == 100:
        break


    index+=1
print(start_abstrak_index)
result_abstrak = ""
if index < 100 and start_abstrak_index != 0 :
    index = start_abstrak_index+1

    for x in range(len(text_arrays)-1-index):
        if 'Kata kunci' in text_arrays[index]:
            start_abstrak_index = index
            break
        if 'Kata Kunci' in text_arrays[index]:
            start_abstrak_index = index
            break
        if 'DAFTAR ISI' in text_arrays[index]:
            start_abstrak_index = index
            break
        result_abstrak += text_arrays[index]
        result_abstrak += ' '
        index+=1

#######################################################
###PROSES AMBIL SUMBER DANA
start_sumber_dana_index  = 0
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Sumber dana' in text_array:
        start_sumber_dana_index = index
        break
    if 'Sumber Dana' in text_array:
        start_sumber_dana_index = index
        break
    index+=1

index = start_sumber_dana_index
result_sumber_dana_array = text_arrays[start_sumber_dana_index].split(',');
r_mentah_sumber_dana = ''
if len(result_sumber_dana_array) > 1:
    index = 0
    print("100000000000000")
    for x in range(10):
        if 'Sumber dana' in result_sumber_dana_array[index]:
            start_sumber_dana_index = index
            break
        if 'Sumber Dana' in result_sumber_dana_array[index]:
            start_sumber_dana_index = index
            break
        index += 1

    r_mentah_sumber_dana = result_sumber_dana_array[start_sumber_dana_index]
else:
    print("200000000000000")
    r_mentah_sumber_dana = result_sumber_dana_array[0]
    if r_mentah_sumber_dana.strip()=="Sumber Dana":
        result_sumber_dana="";print("300000000000000")

result_sumber_dana = ''
if len(r_mentah_sumber_dana.split(":")) > 1:
    result_sumber_dana = r_mentah_sumber_dana.split(":")[1]
else:
    result_sumber_dana = r_mentah_sumber_dana.replace("Sumber Dana","")



#######################################################
###PROSES AMBIL BIAYA PENGABDIAN
r_mentah_biaya = '';
if len(result_sumber_dana_array) > 1 and len(result_sumber_dana_array) !=3:
    for x in result_sumber_dana_array:
        if 'Biaya Pengabdian' in text_array:
            r_mentah_biaya = x.replace('Biaya Pengabdian','').strip()
            break
        r_mentah_biaya = result_sumber_dana_array[1]
        
if len(result_sumber_dana_array) ==3:
    r_mentah_biaya = result_sumber_dana_array[0]+"."+result_sumber_dana_array[1]
    r_mentah_biaya=r_mentah_biaya.replace('Biaya Pengabdian :','')
print("imhereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee",r_mentah_biaya)
if r_mentah_biaya == '':
    start_biaya_index  = 0
    index = 0
    for text_array in text_arrays:
        # print(text_array)
        if 'Biaya Pengabdian' in text_array:
            start_biaya_index = index
            break
        index+=1
    
    if start_biaya_index!=0:
        index = start_biaya_index
        result_biaya_array = text_arrays[start_biaya_index].split(',');
        r_mentah_biaya = ''
        print("imhereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee",result_biaya_array)
        if 2 >= len(result_biaya_array) > 1 :
            index = 0;print("beta")
            for x in range(10):
                if 'Biaya Pengabdian' in result_biaya_array[index]:
                    start_biaya_index = index
                    break
                index += 1
    
            r_mentah_biaya = result_biaya_array[start_biaya_index]
        if len(result_biaya_array) ==3 :
            print("imhereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee")
            r_mentah_biaya = result_biaya_array[0]+"."+result_biaya_array[1]
        if len(result_biaya_array) > 2 :
            
            r_mentah_biaya = text_arrays[start_biaya_index]
        else:
            r_mentah_biaya = result_biaya_array[0];print("alpha")
    else:
        r_mentah_biaya=""
r_mentah_biaya=r_mentah_biaya.replace("+",":").replace("Biaya Pengabdian","")
result_biaya = ''
if len(r_mentah_biaya.split(":")) > 1:
    result_biaya = r_mentah_biaya.split(":")[1]
else:
    result_biaya = r_mentah_biaya
result_biaya=result_biaya.replace("-","").strip()
#######################################################
###PROSES AMBIL LAMA PENGABDIAN
start_lama_pengabdian_index  = 0
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Lama Pengabdian' in text_array:
        start_lama_pengabdian_index = index
        break

    if index == 100:
        break
    index+=1

# print(index)
if index == 100:
    index = 0
    for text_array in text_arrays:
        # print(text_array)
        if 'Lama ' in text_array:
            start_lama_pengabdian_index = index
            break

        if index == 100:
            break
        index+=1

index = start_lama_pengabdian_index
result_lama_pengabdian_array = text_arrays[start_lama_pengabdian_index].split(',');
r_mentah_lama_pengabdian = ''
if start_lama_pengabdian_index!=0:
    if len(result_lama_pengabdian_array) > 1:
        index = 0
        for x in range(10):
            if 'Lama Pengabdian' in result_lama_pengabdian_array[index]:
                start_lama_pengabdian_index = index
                break
            index += 1
    
        r_mentah_lama_pengabdian = result_lama_pengabdian_array[start_lama_pengabdian_index].replace('Lama Pengabdian','')
    else:
        r_mentah_lama_pengabdian = result_lama_pengabdian_array[0].replace('Lama Pengabdian','')
    r_mentah_lama_pengabdian=r_mentah_lama_pengabdian.replace("2",":").replace("+",":")
    result_lama_pengabdian = ''
    if len(r_mentah_lama_pengabdian.split(":")) > 1:
        result_lama_pengabdian = r_mentah_lama_pengabdian.split(":")[1]
    else:
        result_lama_pengabdian = r_mentah_lama_pengabdian
    
    result_lama_pengabdian = result_lama_pengabdian.replace('hart','hari').replace('> han','1 hari').replace('5.','')
    
    
    result_lama_pengabdian_s = result_lama_pengabdian.split(',')
    
    lama_hari  = 0
    lama_bulan = 0
    lama_tahun = 0
    for v in result_lama_pengabdian_s:
        if 'hari' in v:
            lama_hari = re.sub('[^0-9]','', v)
        if 'bulan' in v:
            lama_bulan = re.sub('[^0-9]','', v)
        if 'Bulan' in v:
            lama_bulan = re.sub('[^0-9]','', v)
        if 'tahun' in v:
            lama_tahun = re.sub('[^0-9]','', v)
else :
    lama_bulan, lama_tahun, lama_hari="","",""
    result_lama_pengabdian=""
#######################################################
###PROSES AMBIL MITRA
start_mitra_index  = 0
index = 0
l_type = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Nama Mitra' in text_array:
        start_mitra_index = index
    index+=1
        
index = start_mitra_index
result_mitra = ""
if start_mitra_index != 0 :
    for x in range(10):
        if 'Ketua Pelaksana' in text_arrays[index]:
            start_judul_index = index
            index_ketua_pelaksana = index
            break
        if 'Ketua Tim' in text_arrays[index]:
            start_judul_index = index
            index_ketua_pelaksana = index
            break
        
        result_mitra += text_arrays[index]
        result_mitra += ' '
        index+=1
    result_mitra = result_mitra.replace('Nama Mitra Pengabdian :','')
else :
    result_mitra=""
    
###PROSES LOKASI
index = 0
start_lokasi_index=0
for text_array in text_arrays:
    # print(text_array)
    if 'Lokasi Mitra' in text_array:
        start_lokasi_index = index+1
        break
    if 'Lokasi Pengabdian' in text_array:
        start_lokasi_index = index+1
        break
    index+=1

index = start_lokasi_index
result_lokasi = ""
ab=0
if start_lokasi_index !=0 :
    for x in range(10):
        if 'Luaran' in text_arrays[index]:
            break
        if 'Alamat' in text_arrays[index]:
            break
        if 'Lama Pengabdian' in text_arrays[index]:
            break
        if ab!=0 :
            result_lokasi += ', '
        result_lokasi += text_arrays[index].replace('n,','').replace('o.','').replace('p.','').replace('a,','').replace('b,','').replace('c,','').replace('a.','').replace('b.','').replace('c.','').replace('Propmsi :','').replace('Kelurahan/Kecamatan : ','').replace('Desa/Kecamatan : ','').replace('Kabupaten/Kota : ','').replace('Propinsi : ','').replace('Kabupaten','').replace('h','').replace('Kecamatan','').replace('Provinsi','').replace('Kota :','').replace('Kota','').replace('Tenga','Tengah').replace('nareswan','nareswari').replace('Juwimng','Juwiring').strip()
        
        index+=1
        ab+=1
else:
    result_lokasi=""
print(result_lokasi)


#######################################################
###PROSES AMBIL ANGGOTA DOSEN
start_anggota_dosen_index  = 0
index = 0
d_type = 0
for text_array in text_arrays:
    if 'Nama Anggota' in text_array:
        start_anggota_dosen_index = index
        d_type = 1
        break

    if 'Anggota 1.' in text_array:
        start_anggota_dosen_index = index
        d_type = 2
        break



    index+=1


result_anggota_dosen_s = []
result_anggota_dosen = ""

if d_type==1:
    index = start_anggota_dosen_index
    for x in range(100):
        if 'Nama Anggota' in text_arrays[index]:
            pass
        else:
            start_anggota_dosen_index = index
            break
        result_anggota_dosen_s.append(''.join(i for i in text_arrays[index].replace('Nama Anggota','') if not i.isdigit()));print(result_anggota_dosen_s)
        index+=1

if d_type==2:
    index = start_anggota_dosen_index-1
    for x in range(100):
        if 'Metodologi Pelaksanaan' in text_arrays[index]:
            start_anggota_dosen_index = index
            break
        index+=1
        result_anggota_dosen_s.append(''.join(i for i in text_arrays[index].replace('Anggota','').replace('Metodologi Pelaksanaan','') if not i.isdigit()));print(result_anggota_dosen_s)


print(result_anggota_dosen_s)

#######################################################
###PROSES AMBIL ANGGOTA MAHASISWA
start_anggota_mahasiswa_index  = 0
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Nama Mahasiswa' in text_array:
        start_anggota_mahasiswa_index = index;print("Nama Mahasiswa")
        if 'Mahasiswa terlibat' in text_array:
            start_anggota_mahasiswa_index = index;print('Mahasiswa terlibat')
            break
        else:
            break
    if 'Mahasiswaterlibat' in text_array:
            start_anggota_mahasiswa_index = index+1;print('Mahasiswa terlibat')
            break
    if 'Mahasiswa terlibat' in text_array:
            start_anggota_mahasiswa_index = index+1;print('Mahasiswa terlibatawa')
            break
    if index == 100:
        break
    index+=1

result_anggota_mahasiswa_s = []
result_anggota_mahasiswa_s2 = []
resulte_anggota_mahasiswa = ""
result_nim_mahasiswa_s = []
result_nim_mahasiswa_s2 = []

if start_anggota_mahasiswa_index != 0 :
    if index < 100:
        index = start_anggota_mahasiswa_index
        for x in range(len(text_arrays)-1-index):
            if 'Lokasi Mitra' in text_arrays[index]:
                start_anggota_mahasiswa_index = index
                break
            if 'Lama Pengabdian' in text_arrays[index]:#pengabdian5.pdf
                start_anggota_mahasiswa_index = index
                break
            if 'Lokasi Pengabdian' in text_arrays[index]:#pengabdian5.pdf
                start_anggota_mahasiswa_index = index
                break
            print("kkkkkkkkkkkkkkkkkkkkkkkk",result_anggota_mahasiswa_s2)
            print(start_anggota_mahasiswa_index)
            result_anggota_mahasiswa_s2.append(''.join(i for i in text_arrays[index].replace(',','').replace(':','').replace('_','').replace('Namamahasiswa _:','').replace('Nama Anggota','').replace('Nama Mahasiswa _:','').replace('Nama Mahasiswa :','').replace('Nama Mahasiswa ','').replace('Mahasiswa terlibat','').replace('.','').replace('NIM','').replace('=','')  if not i.isdigit()))
            result_nim_mahasiswa_s2.append(''.join(i for i in text_arrays[index] if i.isdigit()))
            index+=1

else :
    result_anggota_mahasiswa_s=[]
    result_anggota_mahasiswa_s=[]
b=0
for i in range(len(result_anggota_mahasiswa_s2)):
        if result_anggota_mahasiswa_s2[b]!="":
            result_anggota_mahasiswa_s.append(result_anggota_mahasiswa_s2[b])
        b+=1
b=0
for i in range(len(result_nim_mahasiswa_s2)):
        if result_nim_mahasiswa_s2[b]!="":
            result_nim_mahasiswa_s.append(result_nim_mahasiswa_s2[b])    
        b+=1

print("saaaaaaaaaaa",result_anggota_mahasiswa_s)
print("Jenis Pengabdian : ")
print(" ")
print("\n")

print("Judul Pengabdian : ")
print(result_judul.strip())
print("\n")



print("Mitra : ")
print(result_mitra)
print("\n")

print("Lama Pelaksanaan : ")
print(result_lama_pengabdian.strip())
print("\n")

print("Biaya : ")
print(result_biaya)
print("\n")


print("Sumber Dana : ")
print(result_sumber_dana.strip())
print("\n")


print(result_anggota_dosen_s)
print("Anggota Dosen : ")
dsns = []
for dosen in result_anggota_dosen_s:
    dsns.append(removeF(removeF(removeF(dosen))).strip())
print("\n")
print(dsns)
nama_dosen_fix = ambil_nama_dosen(cari_indeks_dosen(dsns))
nip_dosen_fix = ambil_nip_dosen(cari_indeks_dosen(dsns))
print("Anggota Mahasiswa : ",result_anggota_mahasiswa_s)
mhss1 = []
if result_anggota_mahasiswa_s != []:
    for mhs in result_anggota_mahasiswa_s:
        mhss1.append(removeF(removeF(removeF(mhs))).strip())
        print(mhss1)
        if len(mhss1)==0 :
            pass
        elif len(mhss1)==1 :
            if "," in mhss1[0]:
               mhss1=mhss1[0].split(",")
         
        
        else :      
            for i in range(len(mhss1)):
                if "," in mhss1[i]:
                   mhss1=mhss1[i].split(",")
                  
           

print(mhss1)
print("\n")


end = timeit.default_timer();
selisih = end - start;
kecepatan=selisih," s"


data_set = {
    "judul": result_judul.strip(),
    "abstrak": result_abstrak.strip(),
    "Mitra": result_mitra.strip(),
    "lokasi": result_lokasi.strip(),
    "lama_pengabdian": result_lama_pengabdian.strip(),
    "biaya": result_biaya.replace("Rp.","").strip(),
    "sumber_dana": result_sumber_dana.strip(),
    "dosen": nama_dosen_fix,
    "nip": nip_dosen_fix,
    "mahasiswa": mhss1,
    "nim_mhs":result_nim_mahasiswa_s,
    "lama_bulan" : lama_bulan,
    "lama_tahun" : lama_tahun,
    "kecepatan": kecepatan,
    }
print(data_set)

with open(outfile_json, 'w') as outfile_j:
    json.dump(data_set, outfile_j)
