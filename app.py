import psycopg2
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
import arabic_reshaper
from bidi.algorithm import get_display
import sys
import os

# ✅ Charger la police arabe
pdfmetrics.registerFont(TTFont('ArabicFont', 'Amiri-Regular.ttf'))

# ✅ Vérifier l'argument
if len(sys.argv) < 2:
    print("Erreur : id_demande non fourni")
    sys.exit(1)
id_demande = sys.argv[1]

# ✅ Connexion à PostgreSQL
try:
    conn = psycopg2.connect(
        dbname="pfe_bdd",
        user="postgres",
        password="pfe",
        host="localhost",
        port="5432"
    )
except Exception as e:
    print(f"Erreur de connexion à la base de données : {e}")
    sys.exit(1)

cursor = conn.cursor()

# ✅ Requête
cursor.execute("""
    SELECT
        c.num_contrat,
        c.sujet_contrat,
        dem.date_demande,
        di.valeur_prix1,
        di.contenu1,
        di.detail_general,
        tg.libile_gouv,
        r.nom_redacteur,
        r.prenom_redacteur
    FROM "contrats" c
    JOIN "T_demande" dem ON c.id_demande = dem.id_demande
    JOIN "dessin_immobiler1" di ON c.id_demande = di.id_demande
    JOIN "T_gouv" tg ON di.id_demande = tg.id_demande
    JOIN "redacteur" r ON di.id_demande = r.id_demande
    WHERE c.id_demande = %s;
""", (id_demande,))

resultats = cursor.fetchall()
if not resultats:
    print(f"Aucun contrat trouvé pour id_demande={id_demande}")
    sys.exit(1)

# ✅ Chemin PDF (nom unique par ID)
chemin_pdf = f"C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/contrat_{id_demande}.pdf"

# ✅ Créer le canvas PDF
c = canvas.Canvas(chemin_pdf, pagesize=A4)
width, height = A4
c.setFont("ArabicFont", 14)


def draw_arabic_text(text, x, y):
    reshaped = arabic_reshaper.reshape(text)
    bidi_text = get_display(reshaped)
    c.drawRightString(x, y, bidi_text)


# ✅ Remplir le contenu
for row in resultats:
    y = height - 50
    date_demande = f"{row[2]}"
    sujet_contrat = f"{row[1]}"
    num_contrat = f"{row[0]}"
    valeur_prix1 = f"{row[3]}"
    contenu1 = f"{row[4]}"
    detail_general = f"{row[5]}"
    libile_gouv = f"{row[6]}"
    nom_redacteur = f"{row[7]}"
    prenom_redacteur = f"{row[8]}"

    entete = [
        "الجمهورية التونسية",
        "وزارة أملاك الدولة والشؤون العقارية",
        "الديوان الوطني للملكية العقارية",
        f"تونس في {date_demande}"
    ]
    for line in entete:
        draw_arabic_text(line, width - 50, y)
        y -= 20

    y -= 20
    draw_arabic_text("إلى السيد حافظ الملكية العقارية", width - 50, y)
    y -= 20
    draw_arabic_text("تحت إشراف السيد المدير العام للإدارة العامة للإعلامية و الإدارة الالكترونية", width - 50, y)

    y -= 40
    draw_arabic_text(f"الموضوع : {sujet_contrat}", width - 50, y)
    y -= 30

    paragraphe = [
        "تحية طيبة وبعد،",
        f"أنا {prenom_redacteur} {nom_redacteur} محرر(ة) هذا العقد بالإدارة الجهوية للملكية العقارية بـ {libile_gouv}،",
        "أشهد أن الأطراف الواردة هوياتهم أمضوا أمامي وذلك ضمن صلب هذا العقد تحت عدد :",
        f"{num_contrat}",
        "موضوع هذا العقد أن الأطراف المتعاقدة صرّحت بالحالة القانونية الواردة بها،",
        "و عدم وجود أي مانع قانوني للتحرير."
    ]

    for line in paragraphe:
        draw_arabic_text(line, width - 50, y)
        y -= 25
        if y < 100:
            c.showPage()
            c.setFont("ArabicFont", 14)
            y = height - 50

    y -= 20
    draw_arabic_text("الإمضاء", width - 50, y)

# ✅ Sauvegarder
try:
    c.save()
    print(f"✅ PDF généré avec succès à : {chemin_pdf}")
except Exception as e:
    print(f"❌ Erreur lors de la sauvegarde : {e}")
    sys.exit(1)
finally:
    cursor.close()
    conn.close()
