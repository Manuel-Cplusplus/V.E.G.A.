from sklearn.model_selection import KFold

def cross_validate(X, y, n_splits=5, random_state=42):
    
    kf = KFold(n_splits=n_splits, shuffle=True, random_state=random_state)

    for fold, (train_index, test_index) in enumerate(kf.split(X)):
        X_train, X_test = X.iloc[train_index], X.iloc[test_index]
        y_train, y_test = y.iloc[train_index], y.iloc[test_index]

        total_samples = len(X)
        
        #print(f'\nFold {fold + 1}')
        #print('Training data:', len(X_train), f'({(len(X_train) / total_samples) * 100:.2f}%)')
        #print('Test data:', len(X_test), f'({(len(X_test) / total_samples) * 100:.2f}%)')
        
    return X_train, X_test, y_train, y_test
