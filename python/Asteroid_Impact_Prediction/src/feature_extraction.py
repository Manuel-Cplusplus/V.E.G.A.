import pandas as pd
import numpy as np



def feature_selection(X):
    """
    Seleziona solo le feature significative per l'analisi:
    - Calculated Total Impact Energy (kt)
    - Velocity (km/s)
    
    Parametri:
    fireball (pd.DataFrame): Il dataset originale dei fireball

    Ritorna:
    pd.DataFrame: Il dataset ridotto con solo le colonne selezionate
    """
    selected_features = ["Calculated Total Impact Energy (kt)", "Velocity (km/s)", "Total Radiated Energy (J)"]

    # Verifica che tutte le colonne siano presenti
    missing = [col for col in selected_features if col not in X.columns]
    if missing:
        raise ValueError(f"Colonne mancanti nel dataset Fireball: {missing}")
    
    return X[selected_features]



def mathematical_transformation(X):
    '''
    Trasforma l'energia da megatoni a kilotoni
    Parametri:
        X (pd.DataFrame): Il dataset originale
    Ritorna:
        pd.DataFrame: Il dataset con l'energia trasformata in kilotoni
    '''
    
    X['energy'] = X['energy'] * 1000
    return X
    
    