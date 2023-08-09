const bodyParser = require('body-parser');
const mongodb = require('./db/connect');
const express = require('express');
const app = express()

const port = process.env.PORT|| 3000;

app
    .use(bodyParser.json())
    .use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    next();
    })
    .use('/', require('./routes'));

process.on('uncaughtException', (err, origin) => {
    console.log(process.stderr.fd, `Caught exception: ${err}\n` + `Exception origin: ${origin}`);
});

mongodb.initDb((err) => {
    if (err) {
        console.log('Connection failed: ' + err);
    } else {
        app.listen(port);
        console.log(`Connected to DB and listening on ${port}`);
    }
});