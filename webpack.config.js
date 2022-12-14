const webpack = require('webpack');
const path = require('path');
const development = require("./webpack.config.dev");
const production = require("./webpack.conf.prod");

module.exports = (env, argv) => {

    const configuration = argv.mode === 'development' ? development : production;

    return {
        entry: {
            front: './assets/app.ts',
        },
        output: {
            path: path.resolve(__dirname, 'public/build'),
            filename: "./js/[name]-bundle.js",
            publicPath: "/build/",
            clean: true,
        },
        ...configuration,
    }
}


