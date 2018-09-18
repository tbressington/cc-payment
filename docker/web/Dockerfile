FROM debian:jessie

RUN echo mysql-server mysql-server/root_password password password | debconf-set-selections &&\
    echo mysql-server mysql-server/root_password_again password password | debconf-set-selections

RUN apt-get update --fix-missing &&\
    apt-get install -y apt-transport-https lsb-release ca-certificates wget &&\
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg &&\
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list &&\
    apt-get update --fix-missing &&\
    apt-get install -y openssl apache2 php7.2 php7.2-cli php7.2-fpm php7.2-mbstring php7.2-readline php7.2-xml php7.2-common php7.2-gd php7.2-mysql php7.2-soap php7.2-xsl php7.2-curl php7.2-json php7.2-opcache php7.2-zip libapache2-mod-php7.2 vim curl mysql-client mysql-server &&\
    curl -sL https://deb.nodesource.com/setup_8.x | bash - &&\
    apt-get install -y nodejs &&\
    a2enmod rewrite ssl

# Apache configs
COPY web.conf /etc/apache2/sites-enabled/000-default.conf

# Import the DB
CMD /etc/init.d/apache2 start &&\
    /etc/init.d/mysql start &&\
    mysql -uroot -ppassword -e 'create database if not exists crowdcube' &&\
    mysql -uroot -ppassword -e 'grant all on crowdcube.* to "crowdcube"@"%" identified by "crowdcube"' &&\
    bash
