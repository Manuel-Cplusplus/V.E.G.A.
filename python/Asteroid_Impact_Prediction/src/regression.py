from sklearn.ensemble import RandomForestRegressor
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error, mean_absolute_error, r2_score
import numpy as np
from sklearn.neighbors import KNeighborsRegressor

def train_and_evaluate_regression(X_train, X_test, y_train, y_test):
    model = RandomForestRegressor(n_estimators=100, random_state=42)
    model.fit(X_train, y_train)

    y_pred = model.predict(X_test)

    mse = mean_squared_error(y_test, y_pred)
    mae = mean_absolute_error(y_test, y_pred)
    rmse = np.sqrt(mse)
    r2 = r2_score(y_test, y_pred)

    #print(f"\nMetriche di Valutazione del Modello:")
    #print(f"MSE (Mean Squared Error): {mse:.6f}")
    #print(f"MAE (Mean Absolute Error): {mae:.6f}")
    #print(f"RMSE (Root Mean Squared Error): {rmse:.6f}")
    #print(f"R² (R-squared): {r2:.6f}")

    return model, y_pred



def select_model(X_train, X_test, y_train, y_test):
    print("Scegli il modello di regressione:")
    print("1: Linear Regression")
    print("2: KNN (K-Nearest Neighbors)")
    print("3: Random Forest")
    
    model_choice = input("Inserisci il numero corrispondente al modello (1, 2 o 3): ")

    if model_choice == "1":
        model_choice = "LinearRegression"
        model = LinearRegression()
    elif model_choice == "2":
        model_choice = "KNN"
        k_neighbors = int(input("Inserisci il valore di k per KNN: "))
        model = KNeighborsRegressor(n_neighbors=k_neighbors)
    elif model_choice == "3":
        model_choice = "RandomForest"
        model = RandomForestRegressor(n_estimators=100, random_state=42)
    else:
        print("Scelta non valida! Si prega di scegliere 1, 2 o 3.")
        return

    # Allenamento
    model.fit(X_train, y_train)

    # Previsioni
    y_pred_train = model.predict(X_train)
    y_pred_test = model.predict(X_test)

    # Metriche - Train
    mse_train = mean_squared_error(y_train, y_pred_train)
    mae_train = mean_absolute_error(y_train, y_pred_train)
    rmse_train = np.sqrt(mse_train)
    r2_train = r2_score(y_train, y_pred_train)

    # Metriche - Test
    mse_test = mean_squared_error(y_test, y_pred_test)
    mae_test = mean_absolute_error(y_test, y_pred_test)
    rmse_test = np.sqrt(mse_test)
    r2_test = r2_score(y_test, y_pred_test)

    print(f"\n Metriche per il modello {model_choice}:")

    print("\n Training Set:")
    print(f"  MSE: {mse_train:.6f}")
    print(f"  MAE: {mae_train:.6f}")
    print(f"  RMSE: {rmse_train:.6f}")
    print(f"  R²: {r2_train:.6f}")

    print("\n Test Set:")
    print(f"  MSE: {mse_test:.6f}")
    print(f"  MAE: {mae_test:.6f}")
    print(f"  RMSE: {rmse_test:.6f}")
    print(f"  R²: {r2_test:.6f}")

    # Analisi overfitting
    if r2_train - r2_test > 0.2 and r2_test < 0.8:
        print("\n Il modello potrebbe essere in overfitting.")
    elif r2_test > r2_train:
        print("\n Il modello generalizza bene (no overfitting rilevato).")
    else:
        print("\n Il modello sembra bilanciato.")

    return model, y_pred_test