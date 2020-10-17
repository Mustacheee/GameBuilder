FROM php:7.4-cli
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip

RUN apt-get clean
RUN useradd -m webuser
RUN echo 'webuser:1234' | chpasswd

RUN mkdir /home/webuser/app && \
    chown webuser:webuser -R /home/webuser/app

RUN mkdir /home/webuser/.ssh && \
    chown webuser:webuser -R /home/webuser/.ssh && \
    chmod 700 /home/webuser/.ssh

#RUN mkdir -p /home/webuser/app/vendor; chown -R webuser:webuser /home/webuser/app/vendor
WORKDIR /home/webuser/app