import pandas as pd
from sklearn.preprocessing import StandardScaler, MinMaxScaler


def clean_nan(X):
    """
    Pulisce il dataset rimuovendo le righe con valori NaN.

    Parametri:
    fireball (pd.DataFrame): Il dataset originale dei fireball

    Ritorna:
    pd.DataFrame: Il dataset pulito senza valori NaN
    """
    return X.dropna()

def standardize(X):
    """
    Funzione per standardizzare e normalizzare i dati:
    - Standardizzazione dei dati (media=0, deviazione standard=1)
    - Normalizzazione dei dati nell'intervallo [0,1]

    Args:
        X (pd.DataFrame): Dataset originale

    Returns:
        X_standardized: np.ndarray - Dataset standardizzato
        X_normalized: np.ndarray - Dataset normalizzato
    """


    # Standardizzazione dei dati (media=0, deviazione standard=1)
    scaler = StandardScaler()
    X_standardized = scaler.fit_transform(X)

    return pd.DataFrame(X_standardized, columns=X.columns, index=X.index)


def normalize(X):
    '''
    Funzione per normalizzare i dati nell'intervallo [0,1]
    Args:
        X (pd.DataFrame): Dataset originale
    Returns:
        X_normalized: np.ndarray - Dataset normalizzato
    '''

    # Normalizzazione dei dati nell'intervallo [0,1]
    normalizer = MinMaxScaler()
    X_normalized = normalizer.fit_transform(X)

    return pd.DataFrame(X_normalized, columns=X.columns, index=X.index)


def transform_sentry_dataset(sentry_df):
    # Rinomina colonne sentry per corrispondere a quelle usate nel training
    sentry_df = sentry_df.rename(columns={
        'energy': 'Calculated Total Impact Energy (kt)',
        'velocity': 'Velocity (km/s)'
    })

    #Standardizzazione e normalizzazione
    sentry_standard = standardize(sentry_df)
    sentry_normal = normalize(sentry_standard)

    return sentry_normal
