FROM bitnami/laravel

WORKDIR /app

COPY . .

RUN composer install && \
    npm install && \
    npm run build

RUN apt-get update && \
    apt-get install -y netcat-openbsd git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN chmod +x ./entrypoint.sh

CMD [ "./entrypoint.sh" ]