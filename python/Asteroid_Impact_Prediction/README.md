# Asteroid Impact Preditction

## Indice
1. [Descrizione](#descrizione)
2. [Dati di Input](#dati-di-input)
3. [Installazione](#installazione)
4. [Esecuzione](#esecuzione-del-progetto)
5. [Note](#note)

## Descrizione
Ogni evento Sentry presenta due misure chiave:
- Energia di impatto, ovvero la quantità stimata di energia che verrebbe rilasciata in caso di impatto con la Terra. Questo valore è direttamente confrontabile con l’energia di eventi Fireball già accaduti.
- Energia irradiata, che rappresenta la quantità di energia osservata in eventi reali, indicativa della luminosità o “brillanza” del fenomeno.

Poiché l’energia irradiata può differire dall’energia teorica di impatto (a causa di approssimazioni e condizioni fisiche diverse), l' intento è individuare, per ogni evento Sentry analizzato, gli N eventi Fireball più simili. In questo modo sarà possibile stimare la probabile energia irradiata del futuro evento, ottenuta come media dei casi simili.

L’idea è di:
- Recuperare tutti gli eventi Fireball.
- Effettuare una feature selection sul dataset Fireball per identificare le feature più significative.
- Applicare un algoritmo di clustering (ad esempio, K-means) per individuare gruppi di eventi simili. L’ottimizzazione del numero di cluster verrà eseguita tramite metodi come l’elbow method o il silhouette score.
- Successivamente, selezionare un oggetto dal dataset Sentry (tramite API) e confrontarlo con i cluster Fireball, individuando gli eventi passati più simili.
- Rilasciare un elenco di eventi passati simili al possibile evento futuro e effettuare una stima dell'energia che verrà irradiata da tale evento.

In termini pratici, l’analisi assumerà la forma di un sistema predittivo di energia di irradiamento di potenziali impatti futuri basandoci su impatti atmosferici registrati.


___
## Dati di Input
Il progetto si basa sull’analisi di due dataset principali:

1. Dataset "Fireball"
Raccoglie eventi in cui meteore sono entrate nell’atmosfera terrestre a grande velocità, generando esplosioni luminose, talvolta visibili anche di giorno. Non tutti questi eventi comportano un impatto al suolo.
Il dataset completo è disponibile al seguente link: https://cneos.jpl.nasa.gov/fireballs/.

2. Dataset "Sentry"
Contiene dati relativi a potenziali impatti futuri sulla Terra.
Sono scaricabili solo solo un numero limitato di feature dal link: https://cneos.jpl.nasa.gov/sentry/ .
Tuttavia, tramite chiamate API alla modalità “O” del servizio Sentry (https://ssd-api.jpl.nasa.gov/doc/sentry.html), è possibile ottenere più informazioni.

___

## Installazione
1. Per prima cosa, clona il repository:
```bash
git clone https://github.com/Manuel-Cplusplus/Asteroid_Impact_Preditction
```

2. Dopo aver clonato il repository, spostati nella cartella del repository:
- Windows:
```bash
cd Asteroid_Impact_Preditction
```

3. Crea un ambiente virtuale
- Windows:
```bash
python -m venv myenv
```
- Linux/MacOS:
```bash
python3 -m venv myvenv
```

4. Attiva l'ambiente virtuale
- Windows:
```bash
myenv\Scripts\activate
```
- Linux/MacOS:
```bash
source venv/bin/activate
```

5. Installa le dipendenze
```bash
pip install -r requirements.txt
```

___
## Esecuzione del Progetto
Per avviare l'analisi:
```bash
python src/main.py
```

___
## Note
Se aggiungi nuove librerie, aggiorna requirements.txt con:
```bash
pip freeze > requirements.txt
```
