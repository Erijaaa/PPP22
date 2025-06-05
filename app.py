import psycopg2
from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas

# Connexion à la base PostgreSQL
conn = psycopg2.connect(
    dbname="pfe_bdd",
    user="postgres",
    password="pfe",
    host="localhost",
    port="5432"
)
cursor = conn.cursor()

# Requête pour récupérer les données
cursor.execute("""
    SELECT
        di.valeur_prix1,
        di.contenu1,
        tg.libile_gouv,
        t.gouv_titre,
        r.nom_redacteur,
        r.prenom_redacteur,
        acheteur.nom AS nom_acheteur,
        acheteur.prenom AS prenom_acheteur,
        vendeur.nom AS nom_vendeur,
        vendeur.prenom AS prenom_vendeur
    FROM dessin_immobiler1 di
    JOIN T_gouv tg ON di.id_demande = tg.id_demande
    JOIN titres t ON di.id_demande = t.id_demande
    JOIN redacteur r ON di.id_demande = r.id_demande
    JOIN personnes_contracteurs acheteur 
         ON di.id_demande = acheteur.id_demande AND acheteur.vendeur_acheteur = 'المشتري'
    JOIN personnes_contracteurs vendeur 
         ON di.id_demande = vendeur.id_demande AND vendeur.vendeur_acheteur = 'البائع';
""")
resultats = cursor.fetchall()
# Génération du PDF
chemin_pdf = r"C:\Users\erije\OneDrive\Desktop\contrat\rapport_utilisateurs.pdf"
c = canvas.Canvas(chemin_pdf, pagesize=A4)
width, height = A4

c.setFont("Helvetica", 12)
c.drawString(100, height - 40, "Liste des utilisateurs :")

y = height - 80
for row in resultats:
    ligne = f"ID: {row[0]}, Nom: {row[1]}, Email: {row[2]}"
    c.drawString(100, y, ligne)
    y -= 20
    if y < 50:
        c.showPage()
        y = height - 50

c.save()

# Fermeture de la connexion
cursor.close()
conn.close()

print(f"PDF généré avec succès : {chemin_pdf}")
