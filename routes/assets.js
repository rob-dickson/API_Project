const express = require('express');
const router = express.Router();

const myController = require('../controllers/index')
const validation = require('../middleware/validation');

router.get('/', myController.getAssetData);

router.get('/:id', myController.getAssetObject);

router.post('/', myController.createAsset);

router.put('/:id', myController.updateAsset);

router.delete('/:id', myController.deleteAsset);


module.exports = router;
