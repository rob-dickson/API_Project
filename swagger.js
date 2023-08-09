const swaggerAutogen = require('swagger-autogen')();

const doc = {
    info: {
        title: 'Rob Dickson API',
        description: 'Create Assets API',
        copyright: 'AlphaGraphics 2023'
    },
    // host: 'localhost:3000',
    host: 'https://api-project-r7oz.onrender.com/assets',
    schemes: ['https'],
};

const outputFile = './swagger.json';
const endpointsFiles = ['./routes/index.js'];

swaggerAutogen(outputFile, endpointsFiles, doc);