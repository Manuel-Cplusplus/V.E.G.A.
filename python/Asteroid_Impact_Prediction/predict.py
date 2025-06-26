import pickle
import pandas as pd
import sys, json
from src.feature_extraction import mathematical_transformation
from src.preprocessing import transform_sentry_dataset

'''
Questa è una versione utile da usare se c'è bisogno di effettuare passaggi informazioni da sistema esterno
'''

def main():
    try:
        if len(sys.argv) < 2:
            sys.exit(1)

        input_json = sys.argv[1]
        asteroid = json.loads(input_json)
        sentry_df = pd.DataFrame([asteroid])

        sentry_transformed = mathematical_transformation(sentry_df)
        sentry_normalized = transform_sentry_dataset(sentry_transformed)

        with open('models/saved_model.pkl', 'rb') as f:
            model = pickle.load(f)

        with open('models/scaling_params.pkl', 'rb') as f:
            scaling = pickle.load(f)

        predictions_normalized = model.predict(sentry_normalized)

        pred_destandardized = predictions_normalized * (scaling['max_target'] - scaling['min_target']) + scaling['min_target']
        pred_real = pred_destandardized * scaling['std_target'] + scaling['mean_target']

        print(json.dumps({
            "energy": float(pred_real[0])
        }))

    except Exception as e:
        print(json.dumps({ "error": str(e) }))
        sys.exit(1)

if __name__ == "__main__":
    main()
