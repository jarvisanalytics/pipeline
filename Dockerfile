FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive

# Install some apps and docker.
RUN apt-get update && apt-get install -y python3-pip gettext mysql-client
RUN apt-get remove docker docker-engine docker.io && apt install -y docker.io

# Install awscli
RUN pip3 install -U awscli

# Copy entrypoint.
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Install locales.
RUN apt-get install -y locales
RUN locale-gen en_US.UTF-8 en_GB.UTF-8 de_DE.UTF-8 es_ES.UTF-8 fr_FR.UTF-8 it_IT.UTF-8 km_KH sv_SE.UTF-8 fi_FI.UTF-8

# Install PHP 7.3
RUN apt-get -y install software-properties-common && add-apt-repository -y ppa:ondrej/php && apt-get update &&  apt-get -y install php7.3 && apt-get install -y php7.3-cli php7.3-fpm php7.3-bcmath php7.3-curl php7.3-gd php7.3-intl php7.3-json php7.3-mbstring php7.3-mysql php7.3-opcache php7.3-sqlite3 php7.3-xml php7.3-zip

# Install composer.
RUN apt-get install -y wget
RUN wget https://getcomposer.org/download/1.8.6/composer.phar && mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer

# Install aws-iam-authenticator
RUN curl -o aws-iam-authenticator curl -o aws-iam-authenticator https://amazon-eks.s3-us-west-2.amazonaws.com/1.13.7/2019-06-11/bin/linux/amd64/aws-iam-authenticator && chmod +x ./aws-iam-authenticator && mv ./aws-iam-authenticator /usr/local/bin/aws-iam-authenticator

# Install nodejs 12.x
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - && apt-get install -y nodejs

# Copy index.html
COPY index.html /var/www/html/index.html

# Copy cli folder.
COPY cli/ /root/cli/
RUN cd /root/cli && composer install
RUN cp /root/cli/tools.sh /usr/bin/tools
RUN chmod +x /usr/bin/tools

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]