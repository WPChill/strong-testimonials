const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const glob = require( 'glob' );

const isProduction = process.env.NODE_ENV === 'production';

const reactAppEntries = glob
	.sync(
		'./admin/wpchill/apps/notification-system/index.js',
	)
	.reduce( ( acc, file ) => {
		const folderName = path.basename( path.dirname( file ) );
		acc[ folderName ] = `./${ file }`;
		return acc;
	}, {} );

module.exports = {
	...defaultConfig,
	entry: reactAppEntries,
	output: {
		path: path.resolve(
			__dirname,
			'admin/wpchill/scripts/notification-system',
		),
	},
	mode: isProduction ? 'production' : 'development',
};
