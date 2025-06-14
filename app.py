import psycopg2
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
import arabic_reshaper
from bidi.algorithm import get_display
import sys
import os

# Log file for debugging
log_file = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/python_debug.log'


def log(message):
    with open(log_file, 'a', encoding='utf-8') as f:
        f.write(f"{message}\n")


log(f"app.py started: {sys.argv}")

# Load Arabic font
font_path = 'C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/Amiri-Regular.ttf'
if not os.path.exists(font_path):
    log(f"Error: Font file not found at {font_path}")
    print(f"Erreur : Police non trouvée à {font_path}")
    sys.exit(1)

pdfmetrics.registerFont(TTFont('ArabicFont', font_path))
log("Font loaded successfully")

# Check argument
if len(sys.argv) < 2:
    log("Error: id_demande not provided")
    print("Erreur : id_demande non fourni")
    sys.exit(1)
id_demande = sys.argv[1]
log(f"id_demande: {id_demande}")

# Connect to PostgreSQL
try:
    conn = psycopg2.connect(
        dbname="pfe_bdd",
        user="postgres",
        password="pfe",
        host="localhost",
        port="5432"
    )
    log("Database connection established")
except Exception as e:
    log(f"Database connection error: {e}")
    print(f"Erreur de connexion à la base de données : {e}")
    sys.exit(1)

cursor = conn.cursor()

# Query
try:
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
    JOIN "redacteur" r ON r.id_demande = c.id_demande
    WHERE c.id_demande = %s
""", (id_demande,))

    resultats = cursor.fetchall()
    if not resultats:
        log(f"No contract found for id_demande={id_demande}")
        print(f"Aucun contrat trouvé pour id_demande={id_demande}")
        sys.exit(1)
    log("Query executed successfully")
except Exception as e:
    log(f"Query error: {e}")
    print(f"Erreur de requête SQL : {e}")
    sys.exit(1)

# PDF path
chemin_pdf = f"C:/wamp64/www/PFE_erij/PFEEEEEEEEEEEEE/contrat_{id_demande}.pdf"
log(f"PDF path: {chemin_pdf}")

# Create PDF canvas
try:
    c = canvas.Canvas(chemin_pdf, pagesize=A4)
    width, height = A4
    c.setFont("ArabicFont", 14)
    log("PDF canvas created")
except Exception as e:
    log(f"Canvas creation error: {e}")
    print(f"Erreur lors de la création du canvas : {e}")
    sys.exit(1)


def draw_arabic_text(text, x, y):
    reshaped = arabic_reshaper.reshape(text)
    bidi_text = get_display(reshaped)
    c.drawRightString(x, y, bidi_text)


# Fill content
try:
    for row in resultats:
        y = height - 50
        date_demande = f"{row[2]}"
        sujet_contrat = f"{row[1]}"
        id_demande = f"{row[0]}"
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
            f"{id_demande}",
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
    log("PDF content filled")
except Exception as e:
    log(f"Content filling error: {e}")
    print(f"Erreur lors du remplissage du PDF : {e}")
    sys.exit(1)

# Save PDF
try:
    c.save()
    log(f"PDF generated successfully at: {chemin_pdf}")
    print(f"✅ PDF généré avec succès à : {chemin_pdf}")
except Exception as e:
    log(f"Save error: {e}")
    print(f"❌ Erreur lors de la sauvegarde : {e}")
    sys.exit(1)
finally:
    cursor.close()
    conn.close()
    log("Database connection closed")
