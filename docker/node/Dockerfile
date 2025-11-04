FROM node:20-alpine

WORKDIR /var/www

COPY ./src/package*.json ./

RUN npm install

COPY ./src .

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]
