'use strict';

const express = require('express');
const app = express();
const Storage = require('@google-cloud/storage');

// Instantiate a storage client
const storage = Storage();
const bucket = storage.bucket('dance-x-treme-data');

app.use(express.static('static'));

app.get('/', (req, res) => {
  res.status(200).send('Hello, world!').end();
});

app.get('/dance_galleries.txt', (req, res) => {
  //console.log('req', req);
  bucket
    .file('dance_galleries.txt')
    .download()
    .then(data => {

      res.status(200).send(data).end();
    })
    .catch(err => {
      console.error(err);
      res.status(500).end();
    });;




});



// Start the server
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`App listening on port ${PORT}`);
  console.log('Press Ctrl+C to quit.');
});