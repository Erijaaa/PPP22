import psycopg2
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import A4
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
import arabic_reshaper
from bidi.algorithm import get_display

# Enregistrer une police arabe compatible
pdfmetrics.registerFont(TTFont('ArabicFont', 'Amiri-Regular.ttf'))  # Remplace si autre police

# Connexion à PostgreSQL
conn = psycopg2.connect(
    dbname="pfe_bdd",
    user="postgres",
    password="pfe",
    host="localhost",
    port="5432"
)
cursor = conn.cursor()

# Exécuter la requête
cursor.execute("""
    SELECT
    di.valeur_prix1,
    di.contenu1,
    r.nom_redacteur,
    r.prenom_redacteur
    FROM dessin_immobiler1 di
    JOIN redacteur r ON di.id_demande = r.id_demande


""")
resultats = cursor.fetchall()
print("Nombre de lignes retournées :", len(resultats))
print("Résultats récupérés :", resultats)
# Préparer le PDF
chemin_pdf = r"C:\Users\erije\OneDrive\Desktop\contrat\lettre_contrat.pdf"
c = canvas.Canvas(chemin_pdf, pagesize=A4)
width, height = A4
c.setFont("ArabicFont", 14)


def draw_arabic_text(text, x, y):
    reshaped = arabic_reshaper.reshape(text)
    bidi_text = get_display(reshaped)
    c.drawRightString(x, y, bidi_text)


# En-tête
y = height - 50
entete = [
    "الجمهورية التونسية",
    "وزارة أملاك الدولة والشؤون العقارية",
    "الديوان الوطني للملكية العقارية",
    "*****"
]
for line in entete:
    draw_arabic_text(line, width - 50, y)
    y -= 20

y -= 20
draw_arabic_text("إلى السيد حافظ الملكية العقارية", width - 50, y)
y -= 20
draw_arabic_text("تحت إشراف السيد المدير العام للإدارة العامة للإعلامية و الإدارة الالكترونية", width - 50, y)

# Saut de ligne
y -= 40
draw_arabic_text("الموضوع :", width - 50, y)
y -= 30

# Corps pour chaque enregistrement
for row in resultats:
    valeur_prix1 = f"{row[5]}"
    contenu1 = f"{row[4]} "
    nom_redacteur = f"{row[0]} "
    prenom_redacteur = f"{row[1]} "

    paragraphe = [
        f"تحية طيبة و بعد ، أنا {nom_redacteur} محرر هذا العقد بالإدارة الجهوية للملكية العقارية بـ ،",
        f"أشهد أن الأطراف الواردة هوياتهم أعلاه أمضوا أمامي و ضمن ذلك صلب هذا العقد تحت عدد : 12345",
        "موضوع هذا العقد أن الأطراف المتعاقدة صرّحت بالحالة القانونية الواردة بها، و عدم وجود أي مانع قانوني للتحرير."
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
    y -= 40

# Sauvegarder le PDF
c.save()

# Fermer la base
cursor.close()
conn.close()

print(f"✅ PDF généré avec succès à : {chemin_pdf}")
