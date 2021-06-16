import pytesseract
import difflib
import timeit
import itertools
import mysql.connector
# import mysql.connector
import sys
import os
from wand.image import Image
import PIL.Image
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
    thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)[1]

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

outfile_json = add_ons_file+"hasil_teliti.json"
outfile = add_ons_file+"result_teliti.txt"
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
    if data[0] == ",":
        data = data[1:]
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

###PROSES AMBIL JUDUL
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
    if 'Luaran Penelitian' in text_arrays[index]:
        start_judul_index = index
        index_ketua_Ppelaksana = index
        break
    if 'Kode/Nama Rumpun' in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index
        break
    if 'Nama Mitra' in text_arrays[index]:
        start_judul_index = index
        index_ketua_pelaksana = index+1
        break
    result_judul += text_arrays[index]
    result_judul += ' '
    index+=1
result_judul = result_judul.replace('Judul','').replace('Penelitian :','')

###########################################
start_abstrak_index  = 0
index = 0
is_daftar_isi = True

# print("n_ringkasan")
# print(n_ringkasan)
for text_array in text_arrays:
    # print(text_array)
   
    if 'ABSTRAK' in text_array:
        start_abstrak_index = index
        break
    if index == 100:
        break


    index+=1

result_abstrak = ""
if index < 100:
    index = start_abstrak_index+1
    for x in range(len(text_arrays)-1-index):
        if 'Kata kunci' in text_arrays[index]:
            start_abstrak_index = index
            break
        if 'Kata Kunci' in text_arrays[index]:
            start_abstrak_index = index
            break
        if '\x0c' in text_arrays[index]:
            start_abstrak_index = index
            break
        if 'Keywords' in text_arrays[index]:
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
print(result_sumber_dana_array)
r_mentah_sumber_dana = ''
if len(result_sumber_dana_array) > 1:
    index = 0
    for x in range(len(result_sumber_dana_array)):
        if 'Sumber Dana' in result_sumber_dana_array[index]:
            start_sumber_dana_index = index
            print("start A",start_sumber_dana_index)
            break
        if 'Sumber dana' in result_sumber_dana_array[index]:
            start_sumber_dana_index = index
            print("start B",start_sumber_dana_index)
            break

        index += 1

    r_mentah_sumber_dana = result_sumber_dana_array[start_sumber_dana_index]
else:
    r_mentah_sumber_dana = result_sumber_dana_array[0]

result_sumber_dana = ''
if len(r_mentah_sumber_dana.split(":")) > 1:
    result_sumber_dana = r_mentah_sumber_dana.split(":")[1]
else:
    result_sumber_dana = r_mentah_sumber_dana



#######################################################
###PROSES AMBIL BIAYA PENGABDIAN
r_mentah_biaya = '';
if len(result_sumber_dana_array) > 1 and len(result_sumber_dana_array) !=3 :
    for x in result_sumber_dana_array:
        if 'Biaya Penelitian' in text_array:
            r_mentah_biaya = x.replace('Biaya Penelitian','').strip()
            break
        r_mentah_biaya = result_sumber_dana_array[1]
        
if len(result_sumber_dana_array) ==3 :
    r_mentah_biaya = result_sumber_dana_array[0]+"."+result_sumber_dana_array[1]
    r_mentah_biaya=r_mentah_biaya.replace('Biaya Penelitian','').strip()
if len(result_sumber_dana_array) ==4 :
    r_mentah_biaya = result_sumber_dana_array[0]+"."+result_sumber_dana_array[1]+"."+result_sumber_dana_array[2]
    r_mentah_biaya=r_mentah_biaya.replace('Biaya Penelitian','').strip()

if r_mentah_biaya == '':
    start_biaya_index  = 0
    index = 0
    for text_array in text_arrays:
        # print(text_array)
        if 'Biaya Penelitian' in text_array:
            start_biaya_index = index
            break
        index+=1

    index = start_biaya_index
    result_biaya_array = text_arrays[start_biaya_index].split(',');
    r_mentah_biaya = ''
    if len(result_biaya_array) > 1:
        index = 0
        for x in range(len(result_biaya_array)-1):
            if 'Biaya Penelitian' in result_biaya_array[index]:
                start_biaya_index = index
                break
            index += 1

        r_mentah_biaya = result_biaya_array[start_biaya_index]
    else:
        r_mentah_biaya = result_biaya_array[0]
r_mentah_biaya=r_mentah_biaya.replace("+",":")
result_biaya = ''
if len(r_mentah_biaya.split(":")) > 1:
    result_biaya = r_mentah_biaya.split(":")[1].strip()
else:
    result_biaya = r_mentah_biaya.strip()
print(result_biaya)
#######################################################
###PROSES AMBIL LAMA PENGABDIAN
start_lama_penelitian_index  = 0
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Lama Penelitian' in text_array:
        start_lama_penelitian_index = index
        break

    if index == 100:
        break
    index+=1



index = start_lama_penelitian_index
result_lama_penelitian_array = text_arrays[start_lama_penelitian_index].split(',');
r_mentah_lama_penelitian = ''
if len(result_lama_penelitian_array) > 1:
    index = 0
    for x in range(10):
        if 'Lama penelitian' in result_lama_penelitian_array[index]:
            start_lama_penelitian_index = index
            break
        index += 1

    r_mentah_lama_penelitian = result_lama_penelitian_array[start_lama_penelitian_index].replace('Lama Pengabdian','')
else:
    r_mentah_lama_penelitian = result_lama_penelitian_array[0].replace('Lama penelitian','')

result_lama_penelitian = ''
if len(r_mentah_lama_penelitian.split(":")) > 1:
    result_lama_penelitian = r_mentah_lama_penelitian.split(":")[1]
else:
    result_lama_penelitian = r_mentah_lama_penelitian
# print(result_lama_pengabdian)
result_lama_penelitian = result_lama_penelitian.replace('bulan','bulan,').replace('hari','hari,').replace('tahun','tahun,')


result_lama_penelitian_s = result_lama_penelitian.split(',')

lama_hari  = 0
lama_bulan = 0
lama_tahun = 0
for v in result_lama_penelitian_s:
    if 'hari' in v:
        lama_hari = re.sub('[^0-9]','', v)
    if 'bulan' in v:
        lama_bulan = re.sub('[^0-9]','', v)
    if 'tahun' in v:
        lama_tahun = re.sub('[^0-9]','', v)

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

if index!=0 :
    for x in range(10):
        if 'Ketua Pelaksana' in text_arrays[index]:
            start_judul_index = index
            index_ketua_pelaksana = index
            break
        
        result_mitra += text_arrays[index]
        result_mitra += ' '
        index+=1
result_mitra = result_mitra.replace('Nama Mitra Pengabdian','');print(result_mitra.strip())

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
    if 'Anggota Penelitian' in text_array:
        start_anggota_dosen_index = index+1
        d_type = 3
        break



    index+=1


result_anggota_dosen_s = []
result_anggota_dosen = ""
print(d_type)
print("indeksssssssssssssssssssssssssssssssss",start_anggota_dosen_index)

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
if d_type==3:
    index = start_anggota_dosen_index
    
    for index in range(index,100):
        print("proses dosen",index)
        if 'NIP/NIDN ' in text_arrays[index]:
            continue
        if 'Departemen' in text_arrays[index]:
            continue
        if 'Nomor' in text_arrays[index]:
            continue
        if 'Anggota Penelitian' in text_arrays[index]:
            continue
        if 'Anggota Mah' in text_arrays[index]:
            start_anggota_dosen_index = index
            break
        print("proses dosen",result_anggota_dosen_s)
        result_anggota_dosen_s.append(''.join(i for i in text_arrays[index].replace('a. NamaLengkap :','').replace('Nama Lengkap :','') if not i.isdigit()))
        index+=1

if d_type==2:
    index = start_anggota_dosen_index-1
    for x in range(100):
        if 'Metodologi Pelaksanaan' in text_arrays[index]:
            start_anggota_dosen_index = index
            break
        index+=1
        result_anggota_dosen_s.append(''.join(i for i in text_arrays[index].replace('Anggota','').replace('Metodologi Pelaksanaan','') if not i.isdigit()));print(result_anggota_dosen_s)
              


#######################################################
###PROSES AMBIL ANGGOTA MAHASISWA
start_anggota_mahasiswa_index  = 0
index = 0
for text_array in text_arrays:
    # print(text_array)
    if 'Anggota Mahasiswa' in text_array:
        start_anggota_mahasiswa_index = index
        break
    if index == 100:
        break
    index+=1

result_anggota_mahasiswa_s = []
result_nim_mahasiswa_s = []
result_anggota_mahasiswa = ""

if index < 100:
    index = start_anggota_mahasiswa_index
    for x in range(len(text_arrays)-1-index):
        if 'Lama Penelitian' in text_arrays[index]:
            start_anggota_mahasiswa_index = index
            break
        result_anggota_mahasiswa_s.append(''.join(i for i in text_arrays[index].replace('Anggota Mahasiswa :','').replace('Anggota Mahasiswa >','').replace('Nama Anggota','').replace('Mahasiswa terlibat','').replace('NIM','') if not i.isdigit()))
        result_nim_mahasiswa_s.append(''.join(i for i in text_arrays[index] if i.isdigit()))
        index+=1


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
print(result_lama_penelitian.strip())
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
for mhs in result_anggota_mahasiswa_s:
    mhss1.append(removeF(removeF(removeF(mhs))).strip())
    if len(mhss1)==0 :
        pass
    elif len(mhss1)==1 :
        if "," in mhss1[0]:
           mhss1=mhss1[0].split(",")
    
    else :      
        for i in range(len(mhss1)):
            if "," in mhss1[i]:
               mhss1=mhss1[i].split(",")
           

print(len(mhss1))
print("\n")







end = timeit.default_timer();
selisih = end - start;
kecepatan=selisih," s"

data_set = {
    "judul": result_judul.strip(),
    "abstrak": result_abstrak.strip(),
    "Mitra": result_mitra.strip(),
    "lama_pengabdian": result_lama_penelitian.strip(),
    "biaya": result_biaya.replace("Rp.","").strip(),
    "sumber_dana": result_sumber_dana.strip(),
    "dosen": nama_dosen_fix,
    "nip": nip_dosen_fix,
    "mahasiswa": mhss1,
    "nim_mhs": result_nim_mahasiswa_s,
    "lama_bulan" : lama_bulan,
    "lama_tahun" : lama_tahun,
    "kecepatan": kecepatan,
    }
print(data_set)

with open(outfile_json, 'w') as outfile_j:
    json.dump(data_set, outfile_j)
