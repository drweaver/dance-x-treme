'use strict';

const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const Storage = require('@google-cloud/storage');

const DANCE_VENUES = 'dance_venues.txt';
const DANCE_GALLERIES = 'dance_galleries.txt';

// Instantiate a storage client
const storage = Storage();
const bucket = storage.bucket('dance-x-treme-data');

app.use(express.static('static'));
const jsonParser = bodyParser.json();

app.get('/'+DANCE_GALLERIES, (req, res) => {
  getFile(DANCE_GALLERIES)
    .then(data => {
    	console.log('Successfully loaded galleries');
      res.status(200).send(data).end();
    })
    .catch(err => {
      console.error(err);
      res.status(500).end();
    });
});

app.get('/'+DANCE_VENUES, (req, res) => {
  getFile(DANCE_VENUES)
    .then(data => {
    	console.log('Successfully loaded venues');
      res.status(200).send(data).end();
    })
    .catch(err => {
      console.error(err);
      res.status(500).end();
    });
});

app.post('/update_venue.php', jsonParser, (req, res) => {
	saveFile(DANCE_VENUES, JSON.stringify(req.body))
	  .then( () => {
	  	console.log('Successfully saved updated venues');
			res.status(200).end();
	  })
	  .catch( err => {
	  	console.error('Failed to save file to storage', err);
	  	res.status(500).end();
	  });
});

app.post('/update_gallery.php', jsonParser, (req, res) => {
	saveFile(DANCE_GALLERIES, JSON.stringify(req.body))
	  .then( () => {
	  	console.log('Successfully saved updated galleries');
			res.status(200).end();
	  })
	  .catch( err => {
	  	console.error('Failed to save file to storage', err);
	  	res.status(500).end();
	  });
});

function saveFile( name, data ) {
	let file = bucket.file(name);
	return file
		  .save(Buffer.from(data))
		  .then( () => {
		  	return file.makePublic();
		  });
}

function getFile( name ) {
	return new Promise( res => {
	  bucket
    	.file(name)
    	.download()
    	.then(data => {
    		console.log(name, data.toString());
      	 res(data.toString());
    	});
	});
}

// Start the server
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`App listening on port ${PORT}`);
  console.log('Press Ctrl+C to quit.');
});
