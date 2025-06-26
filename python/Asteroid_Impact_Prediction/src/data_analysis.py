'''
import os
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd

def data_analysis(fireball):

    fireball_copy = fireball.copy()
    fireball_copy.pop("Total Radiated Energy (J)")
    # Stampa informazioni sul dataframe
    print(f"\nDataframe shape: {fireball_copy.shape}")
    print(f"\n\nTipi di dati:\n{fireball_copy.dtypes}")
    print(f"\n\nPrime 5 righe del dataframe:\n{fireball_copy.head()}")

    # Controllo valori mancanti
    print(f"\n\nNumero di valori mancanti per feature:\n{fireball_copy.isna().sum()}")

    # Controllo valori negativi (solo per colonne numeriche)
    numeric_cols = fireball_copy.select_dtypes(include=['int64', 'float64'])
    print(f"\n\nNumero di valori negativi per feature numeriche:")
    for col in numeric_cols:
        neg_count = (numeric_cols[col] < 0).sum()
        if neg_count > 0:
            print(f"{col}: {neg_count}")

    # Statistiche descrittive
    print("\n\nStatistiche descrittive:")
    print(fireball_copy.describe())

    # Correlazione tra le feature (solo per colonne numeriche)
    correlation_matrix = fireball_copy.select_dtypes(include=['int64', 'float64']).corr()
    print("\n\nMatrice di correlazione:\n", correlation_matrix)

    # Plot della matrice di correlazione
    fig, ax = plt.subplots(figsize=(10, 8))
    cax = ax.matshow(correlation_matrix, cmap='coolwarm')
    plt.xticks(range(len(correlation_matrix.columns)), correlation_matrix.columns, rotation=60)
    plt.yticks(range(len(correlation_matrix.columns)), correlation_matrix.columns)
    fig.colorbar(cax)

    plt.title('Matrice di Correlazione', pad=20)

    # Creazione della cartella out/figures se non esiste
    output_dir = 'out/figures'
    os.makedirs(output_dir, exist_ok=True)

    # Salvataggio della figura
    fig_path = os.path.join(output_dir, 'correlation_matrix.png')
    plt.savefig(fig_path, bbox_inches='tight')
    print(f"\nLa matrice di correlazione è stata salvata in: {fig_path}")
    # plt.show()

    # Estrazione delle correlazioni maggiori di 0.3 (escludendo la diagonale)
    strong_corr = []

    for i in range(len(correlation_matrix.columns)):
        for j in range(i+1, len(correlation_matrix.columns)):
            if correlation_matrix.iloc[i, j] > 0.3:
                strong_corr.append((correlation_matrix.index[i], correlation_matrix.columns[j], correlation_matrix.iloc[i, j]))

    strong_corr = sorted(strong_corr, key=lambda x: x[2], reverse=True)
    print("\n\nCoppie di feature con correlazione > 0.3:")
    for feature1, feature2, corr_value in strong_corr:
        print(f"{feature1} - {feature2} : {corr_value:.2f}")

    # Conversione delle colonne di tipo object in numeriche (se necessario)
    print("\n\nColonne di tipo 'object' che potrebbero richiedere conversione:")
    object_cols = fireball_copy.select_dtypes(include=['object']).columns
    for col in object_cols:
        print(f"- {col}")
'''