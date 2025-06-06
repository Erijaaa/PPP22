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
        c.num_contrat,
        c.sujet_contrat,
        c.date_contrat,
        di.valeur_prix1,
        di.contenu1,
        di.detail_general,
        tg.libile_gouv,
        r.nom_redacteur,
        r.prenom_redacteur
    FROM "contrats" c
    JOIN "dessin_immobiler1" di ON c.id_demande = di.id_demande
    JOIN "T_gouv" tg ON di.id_demande = tg.id_demande
    JOIN "redacteur" r ON di.id_demande = r.id_demande
""")

resultats = cursor.fetchall()
print("Nombre de lignes retournées :", len(resultats))
print("Résultats récupérés :", resultats)

# Préparer le PDF
chemin_pdf = r"C:\wamp64\www\PFE_erij\PFEEEEEEEEEEEEE\lettre_contrat.pdf"
c = canvas.Canvas(chemin_pdf, pagesize=A4)
width, height = A4
c.setFont("ArabicFont", 14)


def draw_arabic_text(text, x, y):
    reshaped = arabic_reshaper.reshape(text)
    bidi_text = get_display(reshaped)
    c.drawRightString(x, y, bidi_text)


# En-tête
y = height - 50
if resultats:
    row = resultats[0]
    date_contrat = f"{row[2]}"  # Récupère date_contrat pour l'en-tête
    sujet_contrat = f"{row[1]}"  # Récupère sujet_contrat pour le sujet

    entete = [
        "الجمهورية التونسية",
        "وزارة أملاك الدولة والشؤون العقارية",
        "الديوان الوطني للملكية العقارية",
        f"تونس في {date_contrat}"  # Insère la date dynamiquement
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
# Insère le sujet_contrat dynamiquement dans "الموضوع"
if resultats:
    draw_arabic_text(f"الموضوع : {sujet_contrat}", width - 50, y)
else:
    draw_arabic_text("الموضوع : [Sujet non disponible]", width - 50, y)
y -= 30

# Corps pour un seul enregistrement (le premier)
if resultats:
    num_contrat = f"{row[0]}"
    valeur_prix1 = f"{row[3]}"
    contenu1 = f"{row[4]}"
    detail_general = f"{row[5]}"
    libile_gouv = f"{row[6]}"
    nom_redacteur = f"{row[7]}"
    prenom_redacteur = f"{row[8]}"

    paragraphe = [
        "تحية طيبة وبعد،",
        f"أنا {nom_redacteur} {num_contrat} محرر هذا العقد بالإدارة الجهوية للملكية العقارية بـ {libile_gouv}،",
        "أشهد أن الأطراف الواردة هوياتهم أمضوا أمامي وذلك ضمن صلب هذا العقد تحت عدد :",
        f"{nom_redacteur} {prenom_redacteur}",
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

    # Ajouter "الإمضاء" une seule fois
    y -= 20
    draw_arabic_text("الإمضاء", width - 50, y)

# Sauvegarder le PDF avec gestion des erreurs
try:
    c.save()
    print(f"✅ PDF généré avec succès à : {chemin_pdf}")
except PermissionError as e:
    print(f"❌ Erreur : Permission refusée. Vérifiez les permissions ou si le fichier est ouvert. Détails : {e}")
except Exception as e:
    print(f"❌ Erreur inattendue : {e}")
finally:
    cursor.close()
    conn.close()
