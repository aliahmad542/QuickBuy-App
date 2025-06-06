const swaggerJsdoc = require("swagger-jsdoc");
const swaggerUi = require("swagger-ui-express");

const options = {
    definition: {
        openapi: "3.0.0",
        info: {
            title: "API Documentation",
            version: "1.0.0",
            description: "توثيق API باستخدام Swagger",
        },
        servers: [
            {
                url: "http://localhost:3000", // غيّر حسب مشروعك
            },
        ],
    },
    apis: ["./routes/*.js"], // مكان ملفات الراوتر التي تحتوي على توثيق
};

const specs = swaggerJsdoc(options);

module.exports = { swaggerUi, specs };
