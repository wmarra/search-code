FROM php:7.1-apache

RUN a2enmod rewrite
COPY . /var/www/html/

ENV GITHUB_TOKEN your_github_key
ENV SEARCH_CODE_ADAPTER your_provider