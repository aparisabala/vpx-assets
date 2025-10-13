const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const webpack = require("webpack");
const TerserPlugin = require("terser-webpack-plugin");
const pkg = require("./package.json");

module.exports = {
    entry: "./index.js",
    resolve: {
        extensions: [".jsx", ".js", ".json", ".css"],
        modules: ["node_modules"],
        alias: {
            "@app": path.resolve(__dirname, "./src"),
        },
    },
    output: {
        path: path.resolve(__dirname, "dist"),
        library: "PX",
        filename: "px.js",
        libraryTarget: "umd",
        libraryExport: "default",
        globalObject: "this",
        clean: true,
    },
    mode: "production",
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, "css-loader"],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: "babel-loader",
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "px.css",
        }),
        new webpack.BannerPlugin({
            banner: `/*! PX Library v${pkg.version} | For license information please see px.js.LICENSE.txt */`,
            raw: true,
            entryOnly: true,
        }),
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                extractComments: {
                    condition: true,
                    filename: () => `px.js.LICENSE.txt`,
                    banner: (licenseFile) => 
                        `PX Library v${pkg.version} | For license information please see ${licenseFile}`,
                },
            }),
            new CssMinimizerPlugin(),
        ],
    },
};
