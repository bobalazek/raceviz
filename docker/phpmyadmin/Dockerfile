ARG PHPMYADMIN_VERSION=latest

FROM phpmyadmin/phpmyadmin:${PHPMYADMIN_VERSION}

RUN echo -e "\
file_uploads = On\n \
memory_limit = 512M\n \
upload_max_filesize = 512M\n \
post_max_size = 512M\n \
max_execution_time = 600\n \
" > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80
