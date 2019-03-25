const webpack = require('webpack');
const path = require('path');

module.exports = {
	mode: process.env.NODE_ENV,
	entry: './src/index.js',
	output: {
		path: path.resolve(__dirname, 'build'),
		filename: '[name].bundle.js',
		sourceMapFilename: '[name].bundle.map'
	},
	devtool: '#source-map',
	module: {
		rules: [{
			test: /\.js$/,
			exclude: /(node_modules)/,
			loader: 'babel-loader',
			query: {
				presets: ['env', 'stage-0', 'react']
			}
		},
		{
			test: /\.css$/,
			use: ['style-loader', 'css-loader', {
				loader: 'postcss-loader',
				options: {
					plugins: () => [ require('autoprefixer') ]
				}
			}]
		},
		{
			test: /\.scss$/,
			use: ['style-loader', 'css-loader', 'sass-loader', {
				loader: 'postcss-loader',
				options: {
					plugins: () => [ require('autoprefixer') ]
				}
			}]
		},
		{
			test: /\.json$/,
			loader: 'json-loader'
		}]
	},
	plugins: [
		// new webpack.optimize.UglifyJsPlugin({
		// 	sourceMap: true,
		// 	warnings: false,
		// 	mangle: true
		// })
	]
}
