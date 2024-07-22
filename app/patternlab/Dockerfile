FROM node:18.17.0

WORKDIR /patternlab

RUN \
    apt-get update \
    && apt-get install -y --no-install-recommends \
      php8.2 \
    && apt-get clean  \
    && rm -rf /var/lib/apt/lists/*

CMD npm run serve --host 0.0.0.0
