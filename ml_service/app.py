from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import numpy as np

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import make_pipeline

from sklearn.linear_model import LinearRegression
from sklearn.compose import ColumnTransformer
from sklearn.preprocessing import OneHotEncoder
from sklearn.pipeline import Pipeline

app = Flask(__name__)
CORS(app)

MAPPING_CATEGORII = {
    "Salariu": ["Salariu", "salar", "venit", "pfa", "bonus", "avans", "incasare", "munca"],
    "Alocatie Stat": ["alocatie", "stat", "asps", "copil", "indemnizatie"],
    "Alte Venituri": ["olx", "vanzare", "extra", "ramburs", "gasit", "restituire"],
    "Bani din Economii": ["retragere", "economii", "depozit", "cont economii"],
    "Alimente": ["kaufland", "lidl", "mega", "carrefour", "mancare", "profi", "piata", "restaurant", "glovo", "tazz", "supermarket"],
    "Utilitati": ["enel", "digi", "gaz", "apa", "electrica", "factura", "internet", "eon"],
    "Transport": ["benzina", "omv", "petrom", "uber", "bolt", "motorina", "autobuz", "parcare","masina", "tren", "avion"],
    "Sanatate": ["farmacie", "catena", "doctor", "analize", "regina maria", "medicament"],
    "Educatie": ["curs", "carte", "scoala", "gradinita", "rechizite", "meditatii"],
    "Divertisment": ["netflix", "cinema", "bilete", "concert", "hbo", "joc", "pub", "iesire", "spotify"],
    "Imbracaminte": ["haine", "zara", "hm", "mall", "pantof", "adidas", "nike", "puma"],
    "Casa si Intretinere": ["dedeman", "ikea", "leroy", "mobila", "reparatie", "instalator"],
    "Bani de Buzunar": ["buzunar", "bani saptamanali"],
    "Transfer catre Economii": ["economisire", "pusculita", "fond de urgenta"]
}

X_train_nlp = []
y_train_nlp = []
for categorie, cuvinte in MAPPING_CATEGORII.items():
    for cuvant in cuvinte:
        X_train_nlp.append(cuvant)
        y_train_nlp.append(categorie)

print("Antrenăm modelul NLP pentru tranzacții...")
model_nlp = make_pipeline(TfidfVectorizer(analyzer='word', token_pattern=r'(?u)\b\w+\b'), MultinomialNB())
model_nlp.fit(X_train_nlp, y_train_nlp)



print("Antrenăm modelul de Regresie Liniară pentru buget...")

try:
    df_regresie = pd.read_csv('finante_familie_mock.csv')
    

    X_reg = df_regresie.drop('economii_lunare', axis=1)
    y_reg = df_regresie['economii_lunare']

    coloane_categorice = ['categorie_varsta', 'nivel_educatie', 'statut_locuinta', 'domeniu_activitate', 'zona_geografica']
    coloane_numerice = ['venit_lunar', 'cheltuieli_alimente', 'cheltuieli_utilitati', 'cheltuieli_divertisment', 'rate_bancare', 'numar_membri', 'luna_anului']

    preprocessor = ColumnTransformer(
        transformers=[
            ('num', 'passthrough', coloane_numerice),
            ('cat', OneHotEncoder(handle_unknown='ignore'), coloane_categorice)
        ])


    model_regresie = Pipeline(steps=[
        ('preprocessor', preprocessor),
        ('regressor', LinearRegression())
    ])

    model_regresie.fit(X_reg, y_reg)
    print("Modelele au fost antrenate cu succes! ")
except Exception as e:
    print(f"Eroare la încărcarea sau antrenarea regresiei: {e}")



@app.route('/classify', methods=['POST'])
def classify():
    data = request.json
    text = data.get('text', '').lower()
    if not text:
        return jsonify({"category": "Diverse", "confidence": 0.0})

    predicted_category = model_nlp.predict([text])[0]
    probabilities = model_nlp.predict_proba([text])[0]
    confidence = max(probabilities)

    if confidence < 0.15:
        predicted_category = "Diverse"
    
    return jsonify({
        "category": predicted_category,
        "confidence": round(float(confidence), 2)
    })

@app.route('/predict_savings', methods=['POST'])
def predict_savings():
    try:
        date_familie = request.json
        
        df_input = pd.DataFrame([date_familie])
    
        suma_prezisa = model_regresie.predict(df_input)[0]
        
        return jsonify({
            "status": "success",
            "economii_estimate": round(float(suma_prezisa), 2)
        })
    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 400

if __name__ == '__main__':
    print("Microserviciul pornește pe http://127.0.0.1:5000")
    app.run(port=5000, debug=True)