Generate new ssl certificates

    openssl req -x509 -nodes -days 1460 -sha256 -newkey rsa:2048 \
        -subj '/CN=pti-demo.test' \
        -reqexts SAN -extensions SAN \
        -config <(cat /etc/ssl/openssl.cnf \
            <(printf "\n[SAN]\nsubjectAltName=DNS:pti-demo.test,DNS:www.pti-demo.test,DNS:*.pti-demo.test")) \
        -keyout ./certs/nginx.key \
        -out ./certs/nginx.pem
    