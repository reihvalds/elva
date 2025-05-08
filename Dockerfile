FROM yiisoftware/yii2-php:8.3-apache

RUN apt-get update && apt-get install -y \
    unixodbc-dev \
    curl \
    gnupg2 \
    apt-transport-https

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y --allow-unauthenticated \
    msodbcsql18 \
    unixodbc \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install sqlsrv pdo_sqlsrv

RUN docker-php-ext-enable sqlsrv pdo_sqlsrv