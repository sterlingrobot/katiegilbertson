var webpack = require('webpack');

module.exports = {
	entry: './src/index.js',
	output: {
		path: 'dist/assets',
		filename: 'bundle.js',
		sourceMapFilename: 'bundle.map'
	},
	devtool: '#source-map',
	module: {
		rules: [{
			test: /\.js$/,
			exclude: /(node_modules)/,
			loader: ['babel-loader'],
			query: {
				presets: ['env', 'stage-0', 'react']
			}
		},
		{
			test: /\.css$/,
			use: ['style-loader', 'css-loader', {
				loader: 'postcss-loader',
				options: {
					plugins: () => [require('autoprefixer')]
				}
			}]
		},
		{
			test: /\.scss$/,
			use: ['style-loader', 'css-loader', 'sass-loader', {
				loader: 'postcss-loader',
				options: {
					plugins: () => [require('autoprefixer')]
				}
			}]
		},
		{
			test: /\.json$/,
			loader: 'json-loader'
		}]
	},
	plugins: [
		new webpack.optimize.UglifyJsPlugin({
			sourceMap: true,
			warnings: false,
			mangle: true
		})
	]
}
