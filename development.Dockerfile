FROM laravelphp/vapor:php81

RUN apk update && \
    apk add --no-cache \
    nodejs \
    npm \
    chromium \
    harfbuzz \
    freetype \
    ttf-freefont \
    ca-certificates \
    nss \
    exiftool \
        && docker-php-ext-configure exif \
        && docker-php-ext-install exif \
        && docker-php-ext-enable exif

RUN npm install -g puppeteer

COPY . /var/task
