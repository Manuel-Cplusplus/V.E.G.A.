
# Importazione delle librerie
import pandas as pd
import os
import joblib


from src.data_analysis import data_analysis
from src.feature_extraction import feature_selection, mathematical_transformation
from src.preprocessing import clean_nan, normalize, standardize, transform_sentry_dataset
from src.cross_val import cross_validate
from src.regression import select_model, train_and_evaluate_regression



def main():
    # Fase 0: Importazione del dataset Fireball
    print ("\n\n\n ------------------------------------------------------ Dataset Fireball ------------------------------------------------------ \n")
    data_path = "data/raw/cneos_fireball_data.csv"
    if not os.path.exists(data_path):
        print(f"File non trovato: {data_path}")
        return

    # Caricamento del dataset Fireball
    fireball = pd.read_csv(data_path)
    print(f"Dataset caricato con successo: {data_path}")


    print(f"\n\n\n ------------------------- Dataset Sentry ------------------------- \n")
    print("\nVuoi usare gli esempi di default o inserire manualmente i dati?")
    scelta = input("Digita 'd' per default oppure 'm' per inserire manualmente: ").strip().lower()

    if scelta == 'm':
        sentry_data = []
        while True:
            try:
                energia = float(input("Inserisci energia in megatoni (es. 12.5): "))
                velocita = float(input("Inserisci velocità in km/s (es. 34.2): "))
                sentry_data.append({'energy': energia, 'velocity': velocita})
            except ValueError:
                print("Input non valido. Inserisci valori numerici.")
                continue

            altro = input("Vuoi inserire un altro asteroide? (s/n): ").strip().lower()
            if altro != 's':
                break
    else:
        # Valori di Test Dataset Sentry (recuperati tramite chiamate AI)
        # 2010 RF12, 2020 CD3, 2006 RH120, 2024 WS1, 2022 CE5
        sentry_data = [{'energy': 0.008569, 'velocity': 12.26},
                    {'energy': 0.00006842, 'velocity': 11.12},
                    {'energy': 0.001267, 'velocity': 11.13},
                    {'energy': 2.42, 'velocity': 41.76},
                    {'energy': 106.5, 'velocity': 37.27}]

    sentry_df = pd.DataFrame(sentry_data)
    print(f"\nDataset Sentry: \n{sentry_df}")



    # Fase 1: Analisi dei dati
    data_analysis(fireball)


    # Fase 2: Feature Selection
    print("\n\n\n ------------------------- Feature Extraction ------------------------- \n")
    fireball_selected = feature_selection(fireball)
    print(f"\nFeature selezionate: {fireball_selected.columns.tolist()}")

    sentry_transformed = mathematical_transformation(sentry_df)
    print(f"\nFeature trasformate: {sentry_transformed.head()}")


    # Fase 3: Preprocessing
    print(f"\n\n\n ------------------------- Preprocessing ------------------------- \n")
    fireball_cleaned = clean_nan(fireball_selected)
    fireball_standardized = standardize(fireball_cleaned)
    fireball_normalized = normalize(fireball_standardized)
    print(f'\nDati Processati:\n{fireball_normalized[:5]}'+ '\n ...')

    # Recupero target
    fireball_target = fireball_normalized.pop("Total Radiated Energy (J)")
    print(f"\nTarget recuperato: \n{fireball_target[:5]}"+ '\n ...')


    # Fase 4: Cross Validation
    print("\n\n\n ------------------------- Cross Validation ------------------------- \n")
    fireball_train, fireball_test, target_train, target_test = cross_validate(fireball_normalized, fireball_target)


    # Fase 5: Addestramento e validazione
    print("\n\n\n ------------------------- Addestramento e Validazione Modello ------------------------- \n")
    #model, predictions = train_and_evaluate_regression(fireball_train, fireball_test, target_train, target_test)
    model, predictions = select_model(fireball_train, fireball_test, target_train, target_test)


    # Salva il modello addestrato per uso futuro
    joblib.dump(model, 'models/saved_model.pkl')

    # fase 6: Predizione
    print("\n\n\n ------------------------- Predizione Energia Irradiata (Sentry) ------------------------- \n")
    # Normalizziamo Sentry per aveere la stessa scala usata con Fireball
    sentry_normalized = transform_sentry_dataset(sentry_transformed)
    print (f'\nDati Sentry:\n{sentry_transformed}')
    print(f"\nDati Sentry Normalizzati:\n{sentry_normalized}" + '\n')

    # Predizione
    predictions_normalized = model.predict(sentry_normalized)

    # Recupera i valori min/max e media/std.dev del target originale
    min_target = fireball_target.min()
    max_target = fireball_target.max()
    mean_target = fireball_target.mean()
    std_target = fireball_target.std()

    # Prima invertiamo la normalizzazione (min-max)
    predictions_destandardized = predictions_normalized * (max_target - min_target) + min_target

    # Poi invertiamo la standardizzazione (z-score)
    predictions_real = predictions_destandardized * std_target + mean_target

    # Salva i parametri necessari per la trasformazione inversa
    scaling_params = {
        'min_target': min_target,
        'max_target': max_target,
        'mean_target': mean_target,
        'std_target': std_target
    }
    joblib.dump(scaling_params, 'models/scaling_params.pkl')

    # Output predizioni in scala reale
    print("\nValori reali prima della denormalizzazione:")
    for i, pred in enumerate(predictions_normalized):
        print(f"Asteroide {i+1}: Energia Irradiata Predetta (normalizzata) = {pred:.6f} J")

    print("\nValori reali dopo la denormalizzazione:")
    for i, pred in enumerate(predictions_real):
        print(f"Asteroide {i+1}: Energia Irradiata Predetta (valore reale) = {pred:.6f} J")




if __name__ == "__main__":
    main()



