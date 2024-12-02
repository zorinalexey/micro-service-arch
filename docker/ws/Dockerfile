# Используем официальный образ Node.js
FROM node:16

RUN mkdir /opt/ws
COPY ./server/ /opt/ws
RUN chmod 777 -R /opt/ws

WORKDIR /opt/ws

RUN npm install

# Открываем порт
EXPOSE 3000

# Запускаем сервер
CMD ["node", "server.js"]