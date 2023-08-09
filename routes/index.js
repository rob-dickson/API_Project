
const express = require('express');
const router = express.Router();


router.use('/', require('./swagger'));
router.use('/assets', require('./assets'));

module.exports = router;