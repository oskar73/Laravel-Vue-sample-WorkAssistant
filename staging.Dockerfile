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

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/tmp/chromium-browser \
    PUPPETEER_SKIP_DOWNLOAD=true \
    CHROME_BIN=/tmp/chromium-browser \
    CHROME_PATH=/tmp/chromium/

WORKDIR /var/task
RUN npm install puppeteer --dev
WORKDIR /

COPY . /var/task
